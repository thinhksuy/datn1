<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Tests\HttpCache;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\HttpCache\Esi;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\HttpKernel\HttpCache\StoreInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @group time-sensitive
 */
class HttpCacheTest extends HttpCacheTestCase
{
    public function testTerminateDelegatesTerminationOnlyForTerminableInterface()
    {
        $storeMock = $this->getMockBuilder(StoreInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        // does not implement TerminableInterface
        $kernel = new TestKernel();
        $httpCache = new HttpCache($kernel, $storeMock);
        $httpCache->terminate(Request::create('/'), new Response());

        $this->assertFalse($kernel->terminateCalled, 'terminate() is never called if the kernel class does not implement TerminableInterface');

        // implements TerminableInterface
        $kernelMock = $this->getMockBuilder(Kernel::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['terminate', 'registerBundles', 'registerContainerConfiguration'])
            ->getMock();

        $kernelMock->expects($this->once())
            ->method('terminate');

        $kernel = new HttpCache($kernelMock, $storeMock);
        $kernel->terminate(Request::create('/'), new Response());
    }

    public function testDoesNotCallTerminateOnFreshResponse()
    {
        $terminateEvents = [];

        $eventDispatcher = $this->createMock(EventDispatcher::class);
        $eventDispatcher
            ->expects($this->any())
            ->method('dispatch')
            ->with($this->callback(function ($event) use (&$terminateEvents) {
                if ($event instanceof TerminateEvent) {
                    $terminateEvents[] = $event;
                }

                return true;
            }));

        $this->setNextResponse(
            200,
            [
                'ETag' => '1234',
                'Cache-Control' => 'public, s-maxage=60',
            ],
            'Hello World',
            null,
            $eventDispatcher
        );

        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
        $this->cache->terminate($this->request, $this->response);

        sleep(2);

        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('fresh');
        $this->assertEquals(2, $this->response->headers->get('Age'));
        $this->cache->terminate($this->request, $this->response);

        $this->assertCount(1, $terminateEvents);
    }

    public function testPassesOnNonGetHeadRequests()
    {
        $this->setNextResponse(200);
        $this->request('POST', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertResponseOk();
        $this->assertTraceContains('pass');
        $this->assertFalse($this->response->headers->has('Age'));
    }

    public function testPassesSuspiciousMethodRequests()
    {
        $this->setNextResponse(200);
        $this->request('POST', '/', ['HTTP_X-HTTP-Method-Override' => '__CONSTRUCT']);
        $this->assertHttpKernelIsCalled();
        $this->assertResponseOk();
        $this->assertTraceNotContains('stale');
        $this->assertTraceNotContains('invalid');
        $this->assertFalse($this->response->headers->has('Age'));
    }

    public function testInvalidatesOnPostPutDeleteRequests()
    {
        foreach (['post', 'put', 'delete'] as $method) {
            $this->setNextResponse(200);
            $this->request($method, '/');

            $this->assertHttpKernelIsCalled();
            $this->assertResponseOk();
            $this->assertTraceContains('invalidate');
            $this->assertTraceContains('pass');
        }
    }

    public function testDoesNotCacheWithAuthorizationRequestHeaderAndNonPublicResponse()
    {
        $this->setNextResponse(200, ['ETag' => '"Foo"']);
        $this->request('GET', '/', ['HTTP_AUTHORIZATION' => 'basic foobarbaz']);

        $this->assertHttpKernelIsCalled();
        $this->assertResponseOk();
        $this->assertEquals('private', $this->response->headers->get('Cache-Control'));

        $this->assertTraceContains('miss');
        $this->assertTraceNotContains('store');
        $this->assertFalse($this->response->headers->has('Age'));
    }

    public function testDoesCacheWithAuthorizationRequestHeaderAndPublicResponse()
    {
        $this->setNextResponse(200, ['Cache-Control' => 'public', 'ETag' => '"Foo"']);
        $this->request('GET', '/', ['HTTP_AUTHORIZATION' => 'basic foobarbaz']);

        $this->assertHttpKernelIsCalled();
        $this->assertResponseOk();
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
        $this->assertTrue($this->response->headers->has('Age'));
        $this->assertEquals('public', $this->response->headers->get('Cache-Control'));
    }

    public function testDoesNotCacheWithCookieHeaderAndNonPublicResponse()
    {
        $this->setNextResponse(200, ['ETag' => '"Foo"']);
        $this->request('GET', '/', [], ['foo' => 'bar']);

        $this->assertHttpKernelIsCalled();
        $this->assertResponseOk();
        $this->assertEquals('private', $this->response->headers->get('Cache-Control'));
        $this->assertTraceContains('miss');
        $this->assertTraceNotContains('store');
        $this->assertFalse($this->response->headers->has('Age'));
    }

    public function testDoesNotCacheRequestsWithACookieHeader()
    {
        $this->setNextResponse(200);
        $this->request('GET', '/', [], ['foo' => 'bar']);

        $this->assertHttpKernelIsCalled();
        $this->assertResponseOk();
        $this->assertEquals('private', $this->response->headers->get('Cache-Control'));
        $this->assertTraceContains('miss');
        $this->assertTraceNotContains('store');
        $this->assertFalse($this->response->headers->has('Age'));
    }

    public function testRespondsWith304WhenIfModifiedSinceMatchesLastModified()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time());

        $this->setNextResponse(200, ['Cache-Control' => 'public', 'Last-Modified' => $time->format(\DATE_RFC2822), 'Content-Type' => 'text/plain'], 'Hello World');
        $this->request('GET', '/', ['HTTP_IF_MODIFIED_SINCE' => $time->format(\DATE_RFC2822)]);

        $this->assertHttpKernelIsCalled();
        $this->assertEquals(304, $this->response->getStatusCode());
        $this->assertEquals('', $this->response->headers->get('Content-Type'));
        $this->assertSame('', $this->response->getContent());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
    }

    public function testRespondsWith304WhenIfNoneMatchMatchesETag()
    {
        $this->setNextResponse(200, ['Cache-Control' => 'public', 'ETag' => '12345', 'Content-Type' => 'text/plain'], 'Hello World');
        $this->request('GET', '/', ['HTTP_IF_NONE_MATCH' => '12345']);

        $this->assertHttpKernelIsCalled();
        $this->assertEquals(304, $this->response->getStatusCode());
        $this->assertEquals('', $this->response->headers->get('Content-Type'));
        $this->assertTrue($this->response->headers->has('ETag'));
        $this->assertSame('', $this->response->getContent());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
    }

    public function testRespondsWith304WhenIfNoneMatchAndIfModifiedSinceBothMatch()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time());

        $this->setNextResponse(200, [], '', function ($request, $response) use ($time) {
            $response->setStatusCode(200);
            $response->headers->set('ETag', '12345');
            $response->headers->set('Last-Modified', $time->format(\DATE_RFC2822));
            $response->headers->set('Content-Type', 'text/plain');
            $response->setContent('Hello World');
        });

        // only ETag matches
        $t = \DateTimeImmutable::createFromFormat('U', time() - 3600);
        $this->request('GET', '/', ['HTTP_IF_NONE_MATCH' => '12345', 'HTTP_IF_MODIFIED_SINCE' => $t->format(\DATE_RFC2822)]);
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(304, $this->response->getStatusCode());

        // only Last-Modified matches
        $this->request('GET', '/', ['HTTP_IF_NONE_MATCH' => '1234', 'HTTP_IF_MODIFIED_SINCE' => $time->format(\DATE_RFC2822)]);
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());

        // Both matches
        $this->request('GET', '/', ['HTTP_IF_NONE_MATCH' => '12345', 'HTTP_IF_MODIFIED_SINCE' => $time->format(\DATE_RFC2822)]);
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(304, $this->response->getStatusCode());
    }

    public function testIncrementsMaxAgeWhenNoDateIsSpecifiedEventWhenUsingETag()
    {
        $this->setNextResponse(
            200,
            [
                'ETag' => '1234',
                'Cache-Control' => 'public, s-maxage=60',
            ]
        );

        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');

        sleep(2);

        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('fresh');
        $this->assertEquals(2, $this->response->headers->get('Age'));
    }

    public function testValidatesPrivateResponsesCachedOnTheClient()
    {
        $this->setNextResponse(200, [], '', function (Request $request, $response) {
            $etags = preg_split('/\s*,\s*/', $request->headers->get('IF_NONE_MATCH', ''));
            if ($request->cookies->has('authenticated')) {
                $response->headers->set('Cache-Control', 'private, no-store');
                $response->setETag('"private tag"');
                if (\in_array('"private tag"', $etags, true)) {
                    $response->setStatusCode(304);
                } else {
                    $response->setStatusCode(200);
                    $response->headers->set('Content-Type', 'text/plain');
                    $response->setContent('private data');
                }
            } else {
                $response->headers->set('Cache-Control', 'public');
                $response->setETag('"public tag"');
                if (\in_array('"public tag"', $etags, true)) {
                    $response->setStatusCode(304);
                } else {
                    $response->setStatusCode(200);
                    $response->headers->set('Content-Type', 'text/plain');
                    $response->setContent('public data');
                }
            }
        });

        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('"public tag"', $this->response->headers->get('ETag'));
        $this->assertEquals('public data', $this->response->getContent());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');

        $this->request('GET', '/', [], ['authenticated' => '']);
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('"private tag"', $this->response->headers->get('ETag'));
        $this->assertEquals('private data', $this->response->getContent());
        $this->assertTraceContains('stale');
        $this->assertTraceContains('invalid');
        $this->assertTraceNotContains('store');
    }

    public function testStoresResponsesWhenNoCacheRequestDirectivePresent()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time() + 5);

        $this->setNextResponse(200, ['Cache-Control' => 'public', 'Expires' => $time->format(\DATE_RFC2822)]);
        $this->request('GET', '/', ['HTTP_CACHE_CONTROL' => 'no-cache']);

        $this->assertHttpKernelIsCalled();
        $this->assertTraceContains('store');
        $this->assertTrue($this->response->headers->has('Age'));
    }

    public function testReloadsResponsesWhenCacheHitsButNoCacheRequestDirectivePresentWhenAllowReloadIsSetTrue()
    {
        $count = 0;

        $this->setNextResponse(200, ['Cache-Control' => 'public, max-age=10000'], '', function ($request, $response) use (&$count) {
            ++$count;
            $response->setContent(1 == $count ? 'Hello World' : 'Goodbye World');
        });

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('store');

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('fresh');

        $this->cacheConfig['allow_reload'] = true;
        $this->request('GET', '/', ['HTTP_CACHE_CONTROL' => 'no-cache']);
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Goodbye World', $this->response->getContent());
        $this->assertTraceContains('reload');
        $this->assertTraceContains('store');
    }

    public function testDoesNotReloadResponsesWhenAllowReloadIsSetFalseDefault()
    {
        $count = 0;

        $this->setNextResponse(200, ['Cache-Control' => 'public, max-age=10000'], '', function ($request, $response) use (&$count) {
            ++$count;
            $response->setContent(1 == $count ? 'Hello World' : 'Goodbye World');
        });

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('store');

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('fresh');

        $this->cacheConfig['allow_reload'] = false;
        $this->request('GET', '/', ['HTTP_CACHE_CONTROL' => 'no-cache']);
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceNotContains('reload');

        $this->request('GET', '/', ['HTTP_CACHE_CONTROL' => 'no-cache']);
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceNotContains('reload');
    }

    public function testRevalidatesFreshCacheEntryWhenMaxAgeRequestDirectiveIsExceededWhenAllowRevalidateOptionIsSetTrue()
    {
        $count = 0;

        $this->setNextResponse(200, [], '', function ($request, $response) use (&$count) {
            ++$count;
            $response->headers->set('Cache-Control', 'public, max-age=10000');
            $response->setETag($count);
            $response->setContent(1 == $count ? 'Hello World' : 'Goodbye World');
        });

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('store');

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('fresh');

        $this->cacheConfig['allow_revalidate'] = true;
        $this->request('GET', '/', ['HTTP_CACHE_CONTROL' => 'max-age=0']);
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Goodbye World', $this->response->getContent());
        $this->assertTraceContains('stale');
        $this->assertTraceContains('invalid');
        $this->assertTraceContains('store');
    }

    public function testDoesNotRevalidateFreshCacheEntryWhenEnableRevalidateOptionIsSetFalseDefault()
    {
        $count = 0;

        $this->setNextResponse(200, [], '', function ($request, $response) use (&$count) {
            ++$count;
            $response->headers->set('Cache-Control', 'public, max-age=10000');
            $response->setETag($count);
            $response->setContent(1 == $count ? 'Hello World' : 'Goodbye World');
        });

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('store');

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('fresh');

        $this->cacheConfig['allow_revalidate'] = false;
        $this->request('GET', '/', ['HTTP_CACHE_CONTROL' => 'max-age=0']);
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceNotContains('stale');
        $this->assertTraceNotContains('invalid');
        $this->assertTraceContains('fresh');

        $this->request('GET', '/', ['HTTP_CACHE_CONTROL' => 'max-age=0']);
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceNotContains('stale');
        $this->assertTraceNotContains('invalid');
        $this->assertTraceContains('fresh');
    }

    public function testFetchesResponseFromBackendWhenCacheMisses()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time() + 5);
        $this->setNextResponse(200, ['Cache-Control' => 'public', 'Expires' => $time->format(\DATE_RFC2822)]);

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('miss');
        $this->assertTrue($this->response->headers->has('Age'));
    }

    public function testDoesNotCacheSomeStatusCodeResponses()
    {
        foreach (array_merge(range(201, 202), range(204, 206), range(303, 305), range(400, 403), range(405, 409), range(411, 417), range(500, 505)) as $code) {
            $time = \DateTimeImmutable::createFromFormat('U', time() + 5);
            $this->setNextResponse($code, ['Expires' => $time->format(\DATE_RFC2822)]);

            $this->request('GET', '/');
            $this->assertEquals($code, $this->response->getStatusCode());
            $this->assertTraceNotContains('store');
            $this->assertFalse($this->response->headers->has('Age'));
        }
    }

    public function testDoesNotCacheResponsesWithExplicitNoStoreDirective()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time() + 5);
        $this->setNextResponse(200, ['Expires' => $time->format(\DATE_RFC2822), 'Cache-Control' => 'no-store']);

        $this->request('GET', '/');
        $this->assertTraceNotContains('store');
        $this->assertFalse($this->response->headers->has('Age'));
    }

    public function testDoesNotCacheResponsesWithoutFreshnessInformationOrAValidator()
    {
        $this->setNextResponse();

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceNotContains('store');
    }

    public function testCachesResponsesWithExplicitNoCacheDirective()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time() + 5);
        $this->setNextResponse(200, ['Expires' => $time->format(\DATE_RFC2822), 'Cache-Control' => 'public, no-cache']);

        $this->request('GET', '/');
        $this->assertTraceContains('store');
        $this->assertTrue($this->response->headers->has('Age'));
    }

    public function testRevalidatesResponsesWithNoCacheDirectiveEvenIfFresh()
    {
        $this->setNextResponse(200, ['Cache-Control' => 'public, no-cache, max-age=10', 'ETag' => 'some-etag'], 'OK');
        $this->request('GET', '/'); // warm the cache

        sleep(5);

        $this->setNextResponse(304, ['Cache-Control' => 'public, no-cache, max-age=10', 'ETag' => 'some-etag']);
        $this->request('GET', '/');

        $this->assertHttpKernelIsCalled(); // no-cache -> MUST have revalidated at origin
        $this->assertTraceContains('valid');
        $this->assertEquals('OK', $this->response->getContent());
        $this->assertEquals(0, $this->response->getAge());
    }

    public function testCachesResponsesWithAnExpirationHeader()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time() + 5);
        $this->setNextResponse(200, ['Cache-Control' => 'public', 'Expires' => $time->format(\DATE_RFC2822)]);

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertNotNull($this->response->headers->get('Date'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');

        $values = $this->getMetaStorageValues();
        $this->assertCount(1, $values);
    }

    public function testCachesResponsesWithAMaxAgeDirective()
    {
        $this->setNextResponse(200, ['Cache-Control' => 'public, max-age=5']);

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertNotNull($this->response->headers->get('Date'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');

        $values = $this->getMetaStorageValues();
        $this->assertCount(1, $values);
    }

    public function testCachesResponsesWithASMaxAgeDirective()
    {
        $this->setNextResponse(200, ['Cache-Control' => 's-maxage=5']);

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertNotNull($this->response->headers->get('Date'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');

        $values = $this->getMetaStorageValues();
        $this->assertCount(1, $values);
    }

    public function testCachesResponsesWithALastModifiedValidatorButNoFreshnessInformation()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time());
        $this->setNextResponse(200, ['Cache-Control' => 'public', 'Last-Modified' => $time->format(\DATE_RFC2822)]);

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
    }

    public function testCachesResponsesWithAnETagValidatorButNoFreshnessInformation()
    {
        $this->setNextResponse(200, ['Cache-Control' => 'public', 'ETag' => '"123456"']);

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
    }

    public function testHitsCachedResponsesWithExpiresHeader()
    {
        $time1 = \DateTimeImmutable::createFromFormat('U', time() - 5);
        $time2 = \DateTimeImmutable::createFromFormat('U', time() + 5);
        $this->setNextResponse(200, ['Cache-Control' => 'public', 'Date' => $time1->format(\DATE_RFC2822), 'Expires' => $time2->format(\DATE_RFC2822)]);

        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertNotNull($this->response->headers->get('Date'));
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());

        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertLessThan(2, strtotime($this->responses[0]->headers->get('Date')) - strtotime($this->response->headers->get('Date')));
        $this->assertGreaterThan(0, $this->response->headers->get('Age'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertTraceContains('fresh');
        $this->assertTraceNotContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
    }

    public function testHitsCachedResponseWithMaxAgeDirective()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time() - 5);
        $this->setNextResponse(200, ['Date' => $time->format(\DATE_RFC2822), 'Cache-Control' => 'public, max-age=10']);

        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertNotNull($this->response->headers->get('Date'));
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());

        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertLessThan(2, strtotime($this->responses[0]->headers->get('Date')) - strtotime($this->response->headers->get('Date')));
        $this->assertGreaterThan(0, $this->response->headers->get('Age'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertTraceContains('fresh');
        $this->assertTraceNotContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
    }

    public function testDegradationWhenCacheLocked()
    {
        if ('\\' === \DIRECTORY_SEPARATOR) {
            $this->markTestSkipped('Skips on windows to avoid permissions issues.');
        }

        $this->cacheConfig['stale_while_revalidate'] = 10;

        // The presence of Last-Modified makes this cacheable (because Response::isValidateable() then).
        $this->setNextResponse(200, ['Cache-Control' => 'public, s-maxage=5', 'Last-Modified' => 'some while ago'], 'Old response');
        $this->request('GET', '/'); // warm the cache

        // Now, lock the cache
        $concurrentRequest = Request::create('/', 'GET');
        $this->store->lock($concurrentRequest);

        /*
         *  After 10s, the cached response has become stale. Yet, we're still within the "stale_while_revalidate"
         *  timeout so we may serve the stale response.
         */
        sleep(10);

        $this->store = $this->createStore(); // create another store instance that does not hold the current lock
        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('stale-while-revalidate');
        $this->assertEquals('Old response', $this->response->getContent());

        /*
         * Another 10s later, stale_while_revalidate is over. Resort to serving the old response, but
         * do so with a "server unavailable" message.
         */
        sleep(10);

        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(503, $this->response->getStatusCode());
        $this->assertEquals('Old response', $this->response->getContent());
    }

    public function testHitBackendOnlyOnceWhenCacheWasLocked()
    {
        // Disable stale-while-revalidate, it circumvents waiting for the lock
        $this->cacheConfig['stale_while_revalidate'] = 0;

        $this->setNextResponses([
            [
                'status' => 200,
                'body' => 'initial response',
                'headers' => [
                    'Cache-Control' => 'public, no-cache',
                    'Last-Modified' => 'some while ago',
                ],
            ],
            [
                'status' => 304,
                'body' => '',
                'headers' => [
                    'Cache-Control' => 'public, no-cache',
                    'Last-Modified' => 'some while ago',
                ],
            ],
            [
                'status' => 500,
                'body' => 'The backend should not be called twice during revalidation',
                'headers' => [],
            ],
        ]);

        $this->request('GET', '/'); // warm the cache

        // Use a store that simulates a cache entry being locked upon first attempt
        $this->store = new class(sys_get_temp_dir() . '/http_cache') extends Store {
            private bool $hasLock = false;

            public function lock(Request $request): bool
            {
                $hasLock = $this->hasLock;
                $this->hasLock = true;

                return $hasLock;
            }

            public function isLocked(Request $request): bool
            {
                return false;
            }
        };

        $this->request('GET', '/'); // hit the cache with simulated lock/concurrency block

        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('initial response', $this->response->getContent());

        $traces = $this->cache->getTraces();
        $this->assertSame(['stale', 'valid', 'store'], current($traces));
    }

    public function testHitsCachedResponseWithSMaxAgeDirective()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time() - 5);
        $this->setNextResponse(200, ['Date' => $time->format(\DATE_RFC2822), 'Cache-Control' => 's-maxage=10, max-age=0']);

        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertNotNull($this->response->headers->get('Date'));
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());

        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertLessThan(2, strtotime($this->responses[0]->headers->get('Date')) - strtotime($this->response->headers->get('Date')));
        $this->assertGreaterThan(0, $this->response->headers->get('Age'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertTraceContains('fresh');
        $this->assertTraceNotContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
    }

    public function testAssignsDefaultTtlWhenResponseHasNoFreshnessInformation()
    {
        $this->setNextResponse();

        $this->cacheConfig['default_ttl'] = 10;
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertMatchesRegularExpression('/s-maxage=10/', $this->response->headers->get('Cache-Control'));

        $this->cacheConfig['default_ttl'] = 10;
        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('fresh');
        $this->assertTraceNotContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertMatchesRegularExpression('/s-maxage=10/', $this->response->headers->get('Cache-Control'));
    }

    public function testAssignsDefaultTtlWhenResponseHasNoFreshnessInformationAndAfterTtlWasExpired()
    {
        $this->setNextResponse();

        $this->cacheConfig['default_ttl'] = 2;
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertMatchesRegularExpression('/s-maxage=(2|3)/', $this->response->headers->get('Cache-Control'));

        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('fresh');
        $this->assertTraceNotContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertMatchesRegularExpression('/s-maxage=(2|3)/', $this->response->headers->get('Cache-Control'));

        // expires the cache
        $values = $this->getMetaStorageValues();
        $this->assertCount(1, $values);
        $tmp = unserialize($values[0]);
        $time = \DateTimeImmutable::createFromFormat('U', time() - 5);
        $tmp[0][1]['date'] = $time->format(\DATE_RFC2822);
        $r = new \ReflectionObject($this->store);
        $m = $r->getMethod('save');
        $m->invoke($this->store, 'md'.hash('sha256', 'http://localhost/'), serialize($tmp));

        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('stale');
        $this->assertTraceContains('invalid');
        $this->assertTraceContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertMatchesRegularExpression('/s-maxage=(2|3)/', $this->response->headers->get('Cache-Control'));

        $this->setNextResponse();

        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('fresh');
        $this->assertTraceNotContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertMatchesRegularExpression('/s-maxage=(2|3)/', $this->response->headers->get('Cache-Control'));
    }

    public function testAssignsDefaultTtlWhenResponseHasNoFreshnessInformationAndAfterTtlWasExpiredWithStatus304()
    {
        $this->setNextResponse();

        $this->cacheConfig['default_ttl'] = 2;
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertMatchesRegularExpression('/s-maxage=(2|3)/', $this->response->headers->get('Cache-Control'));

        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('fresh');
        $this->assertTraceNotContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());

        // expires the cache
        $values = $this->getMetaStorageValues();
        $this->assertCount(1, $values);
        $tmp = unserialize($values[0]);
        $time = \DateTimeImmutable::createFromFormat('U', time() - 5);
        $tmp[0][1]['date'] = $time->format(\DATE_RFC2822);
        $r = new \ReflectionObject($this->store);
        $m = $r->getMethod('save');
        $m->invoke($this->store, 'md'.hash('sha256', 'http://localhost/'), serialize($tmp));

        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('stale');
        $this->assertTraceContains('valid');
        $this->assertTraceContains('store');
        $this->assertTraceNotContains('miss');
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertMatchesRegularExpression('/s-maxage=(2|3)/', $this->response->headers->get('Cache-Control'));

        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('fresh');
        $this->assertTraceNotContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertMatchesRegularExpression('/s-maxage=(2|3)/', $this->response->headers->get('Cache-Control'));
    }

    public function testDoesNotAssignDefaultTtlWhenResponseHasMustRevalidateDirective()
    {
        $this->setNextResponse(200, ['Cache-Control' => 'must-revalidate']);

        $this->cacheConfig['default_ttl'] = 10;
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('miss');
        $this->assertTraceNotContains('store');
        $this->assertDoesNotMatchRegularExpression('/s-maxage/', $this->response->headers->get('Cache-Control'));
        $this->assertEquals('Hello World', $this->response->getContent());
    }

    public function testFetchesFullResponseWhenCacheStaleAndNoValidatorsPresent()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time() + 5);
        $this->setNextResponse(200, ['Cache-Control' => 'public', 'Expires' => $time->format(\DATE_RFC2822)]);

        // build initial request
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertNotNull($this->response->headers->get('Date'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertNotNull($this->response->headers->get('Age'));
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());

        // go in and play around with the cached metadata directly ...
        $values = $this->getMetaStorageValues();
        $this->assertCount(1, $values);
        $tmp = unserialize($values[0]);
        $time = \DateTimeImmutable::createFromFormat('U', time());
        $tmp[0][1]['expires'] = $time->format(\DATE_RFC2822);
        $r = new \ReflectionObject($this->store);
        $m = $r->getMethod('save');
        $m->invoke($this->store, 'md'.hash('sha256', 'http://localhost/'), serialize($tmp));

        // build subsequent request; should be found but miss due to freshness
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertLessThanOrEqual(1, $this->response->headers->get('Age'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertTraceContains('stale');
        $this->assertTraceNotContains('fresh');
        $this->assertTraceNotContains('miss');
        $this->assertTraceContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
    }

    public function testValidatesCachedResponsesWithLastModifiedAndNoFreshnessInformation()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time());
        $this->setNextResponse(200, [], 'Hello World', function ($request, $response) use ($time) {
            $response->headers->set('Cache-Control', 'public');
            $response->headers->set('Last-Modified', $time->format(\DATE_RFC2822));
            if ($time->format(\DATE_RFC2822) == $request->headers->get('IF_MODIFIED_SINCE')) {
                $response->setStatusCode(304);
                $response->setContent('');
            }
        });

        // build initial request
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertNotNull($this->response->headers->get('Last-Modified'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
        $this->assertTraceNotContains('stale');

        // build subsequent request; should be found but miss due to freshness
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertNotNull($this->response->headers->get('Last-Modified'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertLessThanOrEqual(1, $this->response->headers->get('Age'));
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('stale');
        $this->assertTraceContains('valid');
        $this->assertTraceContains('store');
        $this->assertTraceNotContains('miss');
    }

    public function testValidatesCachedResponsesUseSameHttpMethod()
    {
        $this->setNextResponse(200, [], 'Hello World', function ($request, $response) {
            $this->assertSame('OPTIONS', $request->getMethod());
        });

        // build initial request
        $this->request('OPTIONS', '/');

        // build subsequent request
        $this->request('OPTIONS', '/');
    }

    public function testValidatesCachedResponsesWithETagAndNoFreshnessInformation()
    {
        $this->setNextResponse(200, [], 'Hello World', function ($request, $response) {
            $this->assertFalse($request->headers->has('If-Modified-Since'));
            $response->headers->set('Cache-Control', 'public');
            $response->headers->set('ETag', '"12345"');
            if ($response->getETag() == $request->headers->get('IF_NONE_MATCH')) {
                $response->setStatusCode(304);
                $response->setContent('');
            }
        });

        // build initial request
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertNotNull($this->response->headers->get('ETag'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');

        // build subsequent request; should be found but miss due to freshness
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertNotNull($this->response->headers->get('ETag'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertLessThanOrEqual(1, $this->response->headers->get('Age'));
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('stale');
        $this->assertTraceContains('valid');
        $this->assertTraceContains('store');
        $this->assertTraceNotContains('miss');
    }

    public function testServesResponseWhileFreshAndRevalidatesWithLastModifiedInformation()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time());

        $this->setNextResponse(200, [], 'Hello World', function (Request $request, Response $response) use ($time) {
            $response->setSharedMaxAge(10);
            $response->headers->set('Last-Modified', $time->format(\DATE_RFC2822));
        });

        // prime the cache
        $this->request('GET', '/');

        // next request before s-maxage has expired: Serve from cache
        // without hitting the backend
        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('fresh');

        sleep(15); // expire the cache

        $this->setNextResponse(304, [], '', function (Request $request, Response $response) use ($time) {
            $this->assertEquals($time->format(\DATE_RFC2822), $request->headers->get('IF_MODIFIED_SINCE'));
        });

        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('stale');
        $this->assertTraceContains('valid');
    }

    public function testReplacesCachedResponsesWhenValidationResultsInNon304Response()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time());
        $count = 0;
        $this->setNextResponse(200, [], 'Hello World', function ($request, $response) use ($time, &$count) {
            $response->headers->set('Last-Modified', $time->format(\DATE_RFC2822));
            $response->headers->set('Cache-Control', 'public');
            switch (++$count) {
                case 1:
                    $response->setContent('first response');
                    break;
                case 2:
                    $response->setContent('second response');
                    break;
                case 3:
                    $response->setContent('');
                    $response->setStatusCode(304);
                    break;
            }
        });

        // first request should fetch from backend and store in cache
        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('first response', $this->response->getContent());

        // second request is validated, is invalid, and replaces cached entry
        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('second response', $this->response->getContent());

        // third response is validated, valid, and returns cached entry
        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('second response', $this->response->getContent());

        $this->assertEquals(3, $count);
    }

    public function testPassesHeadRequestsThroughDirectlyOnPass()
    {
        $this->setNextResponse(200, [], 'Hello World', function ($request, $response) {
            $response->setContent('');
            $response->setStatusCode(200);
            $this->assertEquals('HEAD', $request->getMethod());
        });

        $this->request('HEAD', '/', ['HTTP_EXPECT' => 'something ...']);
        $this->assertHttpKernelIsCalled();
        $this->assertEquals('', $this->response->getContent());
    }

    public function testUsesCacheToRespondToHeadRequestsWhenFresh()
    {
        $this->setNextResponse(200, [], 'Hello World', function ($request, $response) {
            $response->headers->set('Cache-Control', 'public, max-age=10');
            $response->setContent('Hello World');
            $response->setStatusCode(200);
            $this->assertNotEquals('HEAD', $request->getMethod());
        });

        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals('Hello World', $this->response->getContent());

        $this->request('HEAD', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('', $this->response->getContent());
        $this->assertEquals(\strlen('Hello World'), $this->response->headers->get('Content-Length'));
    }

    public function testSendsNoContentWhenFresh()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time());
        $this->setNextResponse(200, [], 'Hello World', function ($request, $response) use ($time) {
            $response->headers->set('Cache-Control', 'public, max-age=10');
            $response->headers->set('Last-Modified', $time->format(\DATE_RFC2822));
        });

        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals('Hello World', $this->response->getContent());

        $this->request('GET', '/', ['HTTP_IF_MODIFIED_SINCE' => $time->format(\DATE_RFC2822)]);
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(304, $this->response->getStatusCode());
        $this->assertEquals('', $this->response->getContent());
    }

    public function testInvalidatesCachedResponsesOnPost()
    {
        $this->setNextResponse(200, [], 'Hello World', function ($request, $response) {
            if ('GET' == $request->getMethod()) {
                $response->setStatusCode(200);
                $response->headers->set('Cache-Control', 'public, max-age=500');
                $response->setContent('Hello World');
            } elseif ('POST' == $request->getMethod()) {
                $response->setStatusCode(303);
                $response->headers->set('Location', '/');
                $response->headers->remove('Cache-Control');
                $response->setContent('');
            }
        });

        // build initial request to enter into the cache
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');

        // make sure it is valid
        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('fresh');

        // now POST to same URL
        $this->request('POST', '/helloworld');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals('/', $this->response->headers->get('Location'));
        $this->assertTraceContains('invalidate');
        $this->assertTraceContains('pass');
        $this->assertEquals('', $this->response->getContent());

        // now make sure it was actually invalidated
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('stale');
        $this->assertTraceContains('invalid');
        $this->assertTraceContains('store');
    }

    public function testServesFromCacheWhenHeadersMatch()
    {
        $count = 0;
        $this->setNextResponse(200, ['Cache-Control' => 'max-age=10000'], '', function ($request, $response) use (&$count) {
            $response->headers->set('Vary', 'Accept User-Agent Foo');
            $response->headers->set('Cache-Control', 'public, max-age=10');
            $response->headers->set('X-Response-Count', ++$count);
            $response->setContent($request->headers->get('USER_AGENT'));
        });

        $this->request('GET', '/', ['HTTP_ACCEPT' => 'text/html', 'HTTP_USER_AGENT' => 'Bob/1.0']);
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Bob/1.0', $this->response->getContent());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');

        $this->request('GET', '/', ['HTTP_ACCEPT' => 'text/html', 'HTTP_USER_AGENT' => 'Bob/1.0']);
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Bob/1.0', $this->response->getContent());
        $this->assertTraceContains('fresh');
        $this->assertTraceNotContains('store');
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
    }

    public function testStoresMultipleResponsesWhenHeadersDiffer()
    {
        $count = 0;
        $this->setNextResponse(200, ['Cache-Control' => 'max-age=10000'], '', function ($request, $response) use (&$count) {
            $response->headers->set('Vary', 'Accept User-Agent Foo');
            $response->headers->set('Cache-Control', 'public, max-age=10');
            $response->headers->set('X-Response-Count', ++$count);
            $response->setContent($request->headers->get('USER_AGENT'));
        });

        $this->request('GET', '/', ['HTTP_ACCEPT' => 'text/html', 'HTTP_USER_AGENT' => 'Bob/1.0']);
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Bob/1.0', $this->response->getContent());
        $this->assertEquals(1, $this->response->headers->get('X-Response-Count'));

        $this->request('GET', '/', ['HTTP_ACCEPT' => 'text/html', 'HTTP_USER_AGENT' => 'Bob/2.0']);
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
        $this->assertEquals('Bob/2.0', $this->response->getContent());
        $this->assertEquals(2, $this->response->headers->get('X-Response-Count'));

        $this->request('GET', '/', ['HTTP_ACCEPT' => 'text/html', 'HTTP_USER_AGENT' => 'Bob/1.0']);
        $this->assertTraceContains('fresh');
        $this->assertEquals('Bob/1.0', $this->response->getContent());
        $this->assertEquals(1, $this->response->headers->get('X-Response-Count'));

        $this->request('GET', '/', ['HTTP_ACCEPT' => 'text/html', 'HTTP_USER_AGENT' => 'Bob/2.0']);
        $this->assertTraceContains('fresh');
        $this->assertEquals('Bob/2.0', $this->response->getContent());
        $this->assertEquals(2, $this->response->headers->get('X-Response-Count'));

        $this->request('GET', '/', ['HTTP_USER_AGENT' => 'Bob/2.0']);
        $this->assertTraceContains('miss');
        $this->assertEquals('Bob/2.0', $this->response->getContent());
        $this->assertEquals(3, $this->response->headers->get('X-Response-Count'));
    }

    public function testShouldCatchExceptions()
    {
        $this->catchExceptions();

        $this->setNextResponse();
        $this->request('GET', '/');

        $this->assertExceptionsAreCaught();
    }

    public function testShouldCatchExceptionsWhenReloadingAndNoCacheRequest()
    {
        $this->catchExceptions();

        $this->setNextResponse();
        $this->cacheConfig['allow_reload'] = true;
        $this->request('GET', '/', [], [], false, ['Pragma' => 'no-cache']);

        $this->assertExceptionsAreCaught();
    }

    public function testShouldNotCatchExceptions()
    {
        $this->catchExceptions(false);

        $this->setNextResponse();
        $this->request('GET', '/');

        $this->assertExceptionsAreNotCaught();
    }

    public function testEsiCacheSendsTheLowestTtl()
    {
        $responses = [
            [
                'status' => 200,
                'body' => '<esi:include src="/foo" /> <esi:include src="/bar" />',
                'headers' => [
                    'Cache-Control' => 's-maxage=300',
                    'Surrogate-Control' => 'content="ESI/1.0"',
                ],
            ],
            [
                'status' => 200,
                'body' => 'Hello World!',
                'headers' => ['Cache-Control' => 's-maxage=200'],
            ],
            [
                'status' => 200,
                'body' => 'My name is Bobby.',
                'headers' => ['Cache-Control' => 's-maxage=100'],
            ],
        ];

        $this->setNextResponses($responses);

        $this->request('GET', '/', [], [], true);
        $this->assertEquals('Hello World! My name is Bobby.', $this->response->getContent());

        $this->assertEquals(100, $this->response->getTtl());
    }

    public function testEsiCacheSendsTheLowestTtlForHeadRequests()
    {
        $responses = [
            [
                'status' => 200,
                'body' => 'I am a long-lived main response, but I embed a short-lived resource: <esi:include src="/foo" />',
                'headers' => [
                    'Cache-Control' => 's-maxage=300',
                    'Surrogate-Control' => 'content="ESI/1.0"',
                ],
            ],
            [
                'status' => 200,
                'body' => 'I am a short-lived resource',
                'headers' => ['Cache-Control' => 's-maxage=100'],
            ],
        ];

        $this->setNextResponses($responses);

        $this->request('HEAD', '/', [], [], true);

        $this->assertSame('', $this->response->getContent());
        $this->assertEquals(100, $this->response->getTtl());
    }

    public function testEsiCacheIncludesEmbeddedResponseContentWhenMainResponseFailsRevalidationAndEmbeddedResponseIsFresh()
    {
        $this->setNextResponses([
            [
                'status' => 200,
                'body' => 'main <esi:include src="/foo" />',
                'headers' => [
                    'Cache-Control' => 's-maxage=0', // goes stale immediately
                    'Surrogate-Control' => 'content="ESI/1.0"',
                    'Last-Modified' => 'Mon, 12 Aug 2024 10:00:00 +0000',
                ],
            ],
            [
                'status' => 200,
                'body' => 'embedded',
                'headers' => [
                    'Cache-Control' => 's-maxage=10', // stays fresh
                    'Last-Modified' => 'Mon, 12 Aug 2024 10:05:00 +0000',
                ],
            ],
        ]);

        // prime the cache
        $this->request('GET', '/', [], [], true);
        $this->assertSame(200, $this->response->getStatusCode());
        $this->assertSame('main embedded', $this->response->getContent());
        $this->assertSame('Mon, 12 Aug 2024 10:05:00 +0000', $this->response->getLastModified()->format(\DATE_RFC2822)); // max of both values

        $this->setNextResponses([
            [
                // On the next request, the main response has an updated Last-Modified (main page was modified)...
                'status' => 200,
                'body' => 'main <esi:include src="/foo" />',
                'headers' => [
                    'Cache-Control' => 's-maxage=0',
                    'Surrogate-Control' => 'content="ESI/1.0"',
                    'Last-Modified' => 'Mon, 12 Aug 2024 10:10:00 +0000',
                ],
            ],
            // no revalidation request happens for the embedded response, since it is still fresh
        ]);

        // Re-request with Last-Modified time that we received when the cache was primed
        $this->request('GET', '/', ['HTTP_IF_MODIFIED_SINCE' => 'Mon, 12 Aug 2024 10:05:00 +0000'], [], true);

        $this->assertSame(200, $this->response->getStatusCode());

        // The cache should use the content ("embedded") from the cached entry
        $this->assertSame('main embedded', $this->response->getContent());

        $traces = $this->cache->getTraces();
        $this->assertSame(['stale', 'invalid', 'store'], $traces['GET /']);

        // The embedded resource was still fresh
        $this->assertSame(['fresh'], $traces['GET /foo']);
    }

    public function testEsiCacheIncludesEmbeddedResponseContentWhenMainResponseFailsRevalidationAndEmbeddedResponseIsValid()
    {
        $this->setNextResponses([
            [
                'status' => 200,
                'body' => 'main <esi:include src="/foo" />',
                'headers' => [
                    'Cache-Control' => 's-maxage=0', // goes stale immediately
                    'Surrogate-Control' => 'content="ESI/1.0"',
                    'Last-Modified' => 'Mon, 12 Aug 2024 10:00:00 +0000',
                ],
            ],
            [
                'status' => 200,
                'body' => 'embedded',
                'headers' => [
                    'Cache-Control' => 's-maxage=0', // goes stale immediately
                    'Last-Modified' => 'Mon, 12 Aug 2024 10:05:00 +0000',
                ],
            ],
        ]);

        // prime the cache
        $this->request('GET', '/', [], [], true);
        $this->assertSame(200, $this->response->getStatusCode());
        $this->assertSame('main embedded', $this->response->getContent());
        $this->assertSame('Mon, 12 Aug 2024 10:05:00 +0000', $this->response->getLastModified()->format(\DATE_RFC2822)); // max of both values

        $this->setNextResponses([
            [
                // On the next request, the main response has an updated Last-Modified (main page was modified)...
                'status' => 200,
                'body' => 'main <esi:include src="/foo" />',
                'headers' => [
                    'Cache-Control' => 's-maxage=0',
                    'Surrogate-Control' => 'content="ESI/1.0"',
                    'Last-Modified' => 'Mon, 12 Aug 2024 10:10:00 +0000',
                ],
            ],
            [
                // We have a stale cache entry for the embedded response which will be revalidated.
                // Let's assume the resource did not change, so the controller sends a 304 without content body.
                'status' => 304,
                'body' => '',
                'headers' => [
                    'Cache-Control' => 's-maxage=0',
                ],
            ],
        ]);

        // Re-request with Last-Modified time that we received when the cache was primed
        $this->request('GET', '/', ['HTTP_IF_MODIFIED_SINCE' => 'Mon, 12 Aug 2024 10:05:00 +0000'], [], true);

        $this->assertSame(200, $this->response->getStatusCode());

        // The cache should use the content ("embedded") from the cached entry
        $this->assertSame('main embedded', $this->response->getContent());

        $traces = $this->cache->getTraces();
        $this->assertSame(['stale', 'invalid', 'store'], $traces['GET /']);

        // Check that the embedded resource was successfully revalidated
        $this->assertSame(['stale', 'valid', 'store'], $traces['GET /foo']);
    }

    public function testEsiCacheIncludesEmbeddedResponseContentWhenMainAndEmbeddedResponseAreFresh()
    {
        $this->setNextResponses([
            [
                'status' => 200,
                'body' => 'main <esi:include src="/foo" />',
                'headers' => [
                    'Cache-Control' => 's-maxage=10',
                    'Surrogate-Control' => 'content="ESI/1.0"',
                    'Last-Modified' => 'Mon, 12 Aug 2024 10:05:00 +0000',
                ],
            ],
            [
                'status' => 200,
                'body' => 'embedded',
                'headers' => [
                    'Cache-Control' => 's-maxage=10',
                    'Last-Modified' => 'Mon, 12 Aug 2024 10:00:00 +0000',
                ],
            ],
        ]);

        // prime the cache
        $this->request('GET', '/', [], [], true);
        $this->assertSame(200, $this->response->getStatusCode());
        $this->assertSame('main embedded', $this->response->getContent());
        $this->assertSame('Mon, 12 Aug 2024 10:05:00 +0000', $this->response->getLastModified()->format(\DATE_RFC2822));

        // Assume that a client received 'Mon, 12 Aug 2024 10:00:00 +0000' as last-modified information in the past. This may, for example,
        // be the case when the "main" response at that point had an older Last-Modified time, so the embedded response's Last-Modified time
        // governed the result for the combined response. In other words, the client received a Last-Modified time that still validates the
        // embedded response as of now, but no longer matches the Last-Modified time of the "main" resource.
        // Now this client does a revalidation request.
        $this->request('GET', '/', ['HTTP_IF_MODIFIED_SINCE' => 'Mon, 12 Aug 2024 10:00:00 +0000'], [], true);

        $this->assertSame(200, $this->response->getStatusCode());

        // The cache should use the content ("embedded") from the cached entry
        $this->assertSame('main embedded', $this->response->getContent());

        $traces = $this->cache->getTraces();
        $this->assertSame(['fresh'], $traces['GET /']);

        // Check that the embedded resource was successfully revalidated
        $this->assertSame(['fresh'], $traces['GET /foo']);
    }

    public function testEsiCacheForceValidation()
    {
        $responses = [
            [
                'status' => 200,
                'body' => '<esi:include src="/foo" /> <esi:include src="/bar" />',
                'headers' => [
                    'Cache-Control' => 's-maxage=300',
                    'Surrogate-Control' => 'content="ESI/1.0"',
                ],
            ],
            [
                'status' => 200,
                'body' => 'Hello World!',
                'headers' => ['ETag' => 'foobar'],
            ],
            [
                'status' => 200,
                'body' => 'My name is Bobby.',
                'headers' => ['Cache-Control' => 's-maxage=100'],
            ],
        ];

        $this->setNextResponses($responses);

        $this->request('GET', '/', [], [], true);
        $this->assertEquals('Hello World! My name is Bobby.', $this->response->getContent());
        $this->assertNull($this->response->getTtl());
        $this->assertTrue($this->response->headers->hasCacheControlDirective('private'));
        $this->assertTrue($this->response->headers->hasCacheControlDirective('no-cache'));
    }

    public function testEsiCacheForceValidationForHeadRequests()
    {
        $responses = [
            [
                'status' => 200,
                'body' => 'I am the main response and use expiration caching, but I embed another resource: <esi:include src="/foo" />',
                'headers' => [
                    'Cache-Control' => 's-maxage=300',
                    'Surrogate-Control' => 'content="ESI/1.0"',
                ],
            ],
            [
                'status' => 200,
                'body' => 'I am the embedded resource and use validation caching',
                'headers' => ['ETag' => 'foobar'],
            ],
        ];

        $this->setNextResponses($responses);

        $this->request('HEAD', '/', [], [], true);

        // The response has been assembled from expiration and validation based resources
        // This can neither be cached nor revalidated, so it should be private/no cache
        $this->assertSame('', $this->response->getContent());
        $this->assertNull($this->response->getTtl());
        $this->assertTrue($this->response->headers->hasCacheControlDirective('private'));
        $this->assertTrue($this->response->headers->hasCacheControlDirective('no-cache'));
    }

    public function testEsiRecalculateContentLengthHeader()
    {
        $responses = [
            [
                'status' => 200,
                'body' => '<esi:include src="/foo" />',
                'headers' => [
                    'Content-Length' => 26,
                    'Surrogate-Control' => 'content="ESI/1.0"',
                ],
            ],
            [
                'status' => 200,
                'body' => 'Hello World!',
                'headers' => [],
            ],
        ];

        $this->setNextResponses($responses);

        $this->request('GET', '/', [], [], true);
        $this->assertEquals('Hello World!', $this->response->getContent());
        $this->assertEquals(12, $this->response->headers->get('Content-Length'));
    }

    public function testEsiRecalculateContentLengthHeaderForHeadRequest()
    {
        $responses = [
            [
                'status' => 200,
                'body' => '<esi:include src="/foo" />',
                'headers' => [
                    'Content-Length' => 26,
                    'Surrogate-Control' => 'content="ESI/1.0"',
                ],
            ],
            [
                'status' => 200,
                'body' => 'Hello World!',
                'headers' => [],
            ],
        ];

        $this->setNextResponses($responses);

        $this->request('HEAD', '/', [], [], true);

        // https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.13
        // "The Content-Length entity-header field indicates the size of the entity-body,
        // in decimal number of OCTETs, sent to the recipient or, in the case of the HEAD
        // method, the size of the entity-body that would have been sent had the request
        // been a GET."
        $this->assertSame('', $this->response->getContent());
        $this->assertEquals(12, $this->response->headers->get('Content-Length'));
    }

    public function testClientIpIsAlwaysLocalhostForForwardedRequests()
    {
        $this->setNextResponse();
        $this->request('GET', '/', ['REMOTE_ADDR' => '10.0.0.1']);

        $this->kernel->assert(function ($backendRequest) {
            $this->assertSame('127.0.0.1', $backendRequest->server->get('REMOTE_ADDR'));
        });
    }

    /**
     * @dataProvider getTrustedProxyData
     */
    public function testHttpCacheIsSetAsATrustedProxy(array $existing)
    {
        Request::setTrustedProxies($existing, Request::HEADER_X_FORWARDED_FOR);

        $this->setNextResponse();
        $this->request('GET', '/', ['REMOTE_ADDR' => '10.0.0.1']);
        $this->assertSame($existing, Request::getTrustedProxies());

        $existing = array_unique(array_merge($existing, ['127.0.0.1']));
        $this->kernel->assert(function ($backendRequest) use ($existing) {
            $this->assertSame($existing, Request::getTrustedProxies());
            $this->assertsame('10.0.0.1', $backendRequest->getClientIp());
        });

        Request::setTrustedProxies([], -1);
    }

    public static function getTrustedProxyData()
    {
        return [
            [[]],
            [['10.0.0.2']],
            [['10.0.0.2', '127.0.0.1']],
        ];
    }

    /**
     * @dataProvider getForwardedData
     */
    public function testForwarderHeaderForForwardedRequests($forwarded, $expected)
    {
        $this->setNextResponse();
        $server = ['REMOTE_ADDR' => '10.0.0.1'];
        if (null !== $forwarded) {
            Request::setTrustedProxies($server, -1);
            $server['HTTP_FORWARDED'] = $forwarded;
        }
        $this->request('GET', '/', $server);

        $this->kernel->assert(function ($backendRequest) use ($expected) {
            $this->assertSame($expected, $backendRequest->headers->get('Forwarded'));
        });

        Request::setTrustedProxies([], -1);
    }

    public static function getForwardedData()
    {
        return [
            [null, 'for="10.0.0.1";host="localhost";proto=http'],
            ['for=10.0.0.2', 'for="10.0.0.2";host="localhost";proto=http, for="10.0.0.1"'],
            ['for=10.0.0.2, for=10.0.0.3', 'for="10.0.0.2";host="localhost";proto=http, for="10.0.0.3", for="10.0.0.1"'],
        ];
    }

    public function testEsiCacheRemoveValidationHeadersIfEmbeddedResponses()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time());

        $responses = [
            [
                'status' => 200,
                'body' => '<esi:include src="/hey" />',
                'headers' => [
                    'Surrogate-Control' => 'content="ESI/1.0"',
                    'ETag' => 'hey',
                    'Last-Modified' => $time->format(\DATE_RFC2822),
                ],
            ],
            [
                'status' => 200,
                'body' => 'Hey!',
                'headers' => [],
            ],
        ];

        $this->setNextResponses($responses);

        $this->request('GET', '/', [], [], true);
        $this->assertNull($this->response->getETag());
        $this->assertNull($this->response->getLastModified());
    }

    public function testEsiCacheRemoveValidationHeadersIfEmbeddedResponsesAndHeadRequest()
    {
        $time = \DateTimeImmutable::createFromFormat('U', time());

        $responses = [
            [
                'status' => 200,
                'body' => '<esi:include src="/hey" />',
                'headers' => [
                    'Surrogate-Control' => 'content="ESI/1.0"',
                    'ETag' => 'hey',
                    'Last-Modified' => $time->format(\DATE_RFC2822),
                ],
            ],
            [
                'status' => 200,
                'body' => 'Hey!',
                'headers' => [],
            ],
        ];

        $this->setNextResponses($responses);

        $this->request('HEAD', '/', [], [], true);
        $this->assertSame('', $this->response->getContent());
        $this->assertNull($this->response->getETag());
        $this->assertNull($this->response->getLastModified());
    }

    public function testDoesNotCacheOptionsRequest()
    {
        $this->setNextResponse(200, ['Cache-Control' => 'public, s-maxage=60'], 'get');
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();

        $this->setNextResponse(200, ['Cache-Control' => 'public, s-maxage=60'], 'options');
        $this->request('OPTIONS', '/');
        $this->assertHttpKernelIsCalled();

        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertSame('get', $this->response->getContent());
    }

    public function testUsesOriginalRequestForSurrogate()
    {
        $kernel = $this->createMock(HttpKernelInterface::class);
        $store = $this->createMock(StoreInterface::class);

        $kernel
            ->expects($this->exactly(2))
            ->method('handle')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame('127.0.0.1', $request->server->get('REMOTE_ADDR'));

                return new Response();
            });

        $cache = new HttpCache($kernel,
            $store,
            new Esi()
        );

        $request = Request::create('/');
        $request->server->set('REMOTE_ADDR', '10.0.0.1');

        // Main request
        $cache->handle($request, HttpKernelInterface::MAIN_REQUEST);

        // Main request was now modified by HttpCache
        // The surrogate will ask for the request using $this->cache->getRequest()
        // which MUST return the original request so the surrogate
        // can actually behave like a reverse proxy like e.g. Varnish would.
        $this->assertSame('10.0.0.1', $cache->getRequest()->getClientIp());
        $this->assertSame('10.0.0.1', $cache->getRequest()->server->get('REMOTE_ADDR'));

        // Surrogate request
        $cache->handle($request, HttpKernelInterface::SUB_REQUEST);
    }

    public function testStaleIfErrorMustNotResetLifetime()
    {
        // Make sure we don't accidentally treat the response as fresh (revalidated) again
        // when stale-if-error handling kicks in.

        $responses = [
            [
                'status' => 200,
                'body' => 'OK',
                // This is cacheable and can be used in stale-if-error cases:
                'headers' => ['Cache-Control' => 'public, max-age=10', 'ETag' => 'some-etag'],
            ],
            [
                'status' => 500,
                'body' => 'FAIL',
                'headers' => [],
            ],
            [
                'status' => 500,
                'body' => 'FAIL',
                'headers' => [],
            ],
        ];

        $this->setNextResponses($responses);
        $this->cacheConfig['stale_if_error'] = 10;

        $this->request('GET', '/'); // warm cache

        sleep(15); // now the entry is stale, but still within the grace period (10s max-age + 10s stale-if-error)

        $this->request('GET', '/'); // hit backend error
        $this->assertEquals(200, $this->response->getStatusCode()); // stale-if-error saved the day
        $this->assertEquals(15, $this->response->getAge());

        sleep(10); // now we're outside the grace period

        $this->request('GET', '/'); // hit backend error
        $this->assertEquals(500, $this->response->getStatusCode()); // fail
    }

    /**
     * @dataProvider getResponseDataThatMayBeServedStaleIfError
     */
    public function testResponsesThatMayBeUsedStaleIfError($responseHeaders, $sleepBetweenRequests = null)
    {
        $responses = [
            [
                'status' => 200,
                'body' => 'OK',
                'headers' => $responseHeaders,
            ],
            [
                'status' => 500,
                'body' => 'FAIL',
                'headers' => [],
            ],
        ];

        $this->setNextResponses($responses);
        $this->cacheConfig['stale_if_error'] = 10; // after stale, may be served for 10s

        $this->request('GET', '/'); // warm cache

        if ($sleepBetweenRequests) {
            sleep($sleepBetweenRequests);
        }

        $this->request('GET', '/'); // hit backend error

        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('OK', $this->response->getContent());
        $this->assertTraceContains('stale-if-error');
    }

    public static function getResponseDataThatMayBeServedStaleIfError()
    {
        // All data sets assume that a 10s stale-if-error grace period has been configured
        yield 'public, max-age expired' => [['Cache-Control' => 'public, max-age=60'], 65];
        yield 'public, validateable with ETag, no TTL' => [['Cache-Control' => 'public', 'ETag' => 'some-etag'], 5];
        yield 'public, validateable with Last-Modified, no TTL' => [['Cache-Control' => 'public', 'Last-Modified' => 'yesterday'], 5];
        yield 'public, s-maxage will be served stale-if-error, even if the RFC mandates otherwise' => [['Cache-Control' => 'public, s-maxage=20'], 25];
    }

    /**
     * @dataProvider getResponseDataThatMustNotBeServedStaleIfError
     */
    public function testResponsesThatMustNotBeUsedStaleIfError($responseHeaders, $sleepBetweenRequests = null)
    {
        $responses = [
            [
                'status' => 200,
                'body' => 'OK',
                'headers' => $responseHeaders,
            ],
            [
                'status' => 500,
                'body' => 'FAIL',
                'headers' => [],
            ],
        ];

        $this->setNextResponses($responses);
        $this->cacheConfig['stale_if_error'] = 10; // after stale, may be served for 10s
        $this->cacheConfig['strict_smaxage'] = true; // full RFC compliance for this feature

        $this->request('GET', '/'); // warm cache

        if ($sleepBetweenRequests) {
            sleep($sleepBetweenRequests);
        }

        $this->request('GET', '/'); // hit backend error

        $this->assertEquals(500, $this->response->getStatusCode());
    }

    public function testSkipsConfiguredResponseHeadersForStore()
    {
        $storeMock = $this->createMock(StoreInterface::class);
        $storeMock
            ->expects($this->once())
            ->method('write')
            ->with(
                $this->isInstanceOf(Request::class),
                $this->callback(function (Response $response) {
                    $this->assertFalse($response->headers->has('Set-Cookie'));
                    $this->assertFalse($response->headers->has('Another-One-To-Skip'));
                    $this->assertTrue($response->headers->has('Cache-Control'));
                    $this->assertTrue($response->headers->has('Another-One-To-Keep'));

                    return true;
                })
            );

        $this->setNextResponse(200, [
            'Cache-Control' => 'public, s-maxage=20',
            'Set-Cookie' => 'foobar=value; path=/',
            'Another-One-To-Skip' => 'foobar',
            'Another-One-To-Keep' => 'foobar',
        ]);

        $httpCache = new HttpCache($this->kernel, $storeMock, null, [
            'skip_response_headers' => ['Set-Cookie', 'Another-One-To-Skip', 'I-do-Not-Exist'],
        ]);

        $response = $httpCache->handle(Request::create('/'));

        $this->assertSame('foobar=value; path=/', $response->headers->get('Set-Cookie'));
        $this->assertSame('foobar', $response->headers->get('Another-One-To-Skip'));
        $this->assertSame('foobar', $response->headers->get('Another-One-To-Keep'));
        $this->assertFalse($response->headers->has('I-do-Not-Exist'));
    }

    public static function getResponseDataThatMustNotBeServedStaleIfError()
    {
        // All data sets assume that a 10s stale-if-error grace period has been configured
        yield 'public, no TTL but beyond grace period' => [['Cache-Control' => 'public'], 15];
        yield 'public, validateable with ETag, no TTL but beyond grace period' => [['Cache-Control' => 'public', 'ETag' => 'some-etag'], 15];
        yield 'public, validateable with Last-Modified, no TTL but beyond grace period' => [['Cache-Control' => 'public', 'Last-Modified' => 'yesterday'], 15];
        yield 'public, stale beyond grace period' => [['Cache-Control' => 'public, max-age=10'], 30];

        // Cache-control values that prohibit serving stale responses or responses without positive validation -
        // see https://tools.ietf.org/html/rfc7234#section-4.2.4 and
        // https://tools.ietf.org/html/rfc7234#section-5.2.2
        yield 'no-cache requires positive validation' => [['Cache-Control' => 'public, no-cache', 'ETag' => 'some-etag']];
        yield 'no-cache requires positive validation, even if fresh' => [['Cache-Control' => 'public, no-cache, max-age=10']];
        yield 'must-revalidate requires positive validation once stale' => [['Cache-Control' => 'public, max-age=10, must-revalidate'], 15];
        yield 'proxy-revalidate requires positive validation once stale' => [['Cache-Control' => 'public, max-age=10, proxy-revalidate'], 15];
    }

    public function testStaleIfErrorWhenStrictSmaxageDisabled()
    {
        $responses = [
            [
                'status' => 200,
                'body' => 'OK',
                'headers' => ['Cache-Control' => 'public, s-maxage=20'],
            ],
            [
                'status' => 500,
                'body' => 'FAIL',
                'headers' => [],
            ],
        ];

        $this->setNextResponses($responses);
        $this->cacheConfig['stale_if_error'] = 10;
        $this->cacheConfig['strict_smaxage'] = false;

        $this->request('GET', '/'); // warm cache
        sleep(25);
        $this->request('GET', '/'); // hit backend error

        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('OK', $this->response->getContent());
        $this->assertTraceContains('stale-if-error');
    }

    public function testTraceHeaderNameCanBeChanged()
    {
        $this->cacheConfig['trace_header'] = 'X-My-Header';
        $this->setNextResponse();
        $this->request('GET', '/');

        $this->assertTrue($this->response->headers->has('X-My-Header'));
    }

    public function testTraceLevelDefaultsToFullIfDebug()
    {
        $this->setNextResponse();
        $this->request('GET', '/');

        $this->assertTrue($this->response->headers->has('X-Symfony-Cache'));
        $this->assertEquals('GET /: miss', $this->response->headers->get('X-Symfony-Cache'));
    }

    public function testTraceLevelDefaultsToNoneIfNotDebug()
    {
        $this->cacheConfig['debug'] = false;
        $this->setNextResponse();
        $this->request('GET', '/');

        $this->assertFalse($this->response->headers->has('X-Symfony-Cache'));
    }

    public function testTraceLevelShort()
    {
        $this->cacheConfig['trace_level'] = 'short';

        $this->setNextResponse();
        $this->request('GET', '/');

        $this->assertTrue($this->response->headers->has('X-Symfony-Cache'));
        $this->assertEquals('miss', $this->response->headers->get('X-Symfony-Cache'));
    }
}

class TestKernel implements HttpKernelInterface
{
    public bool $terminateCalled = false;

    public function terminate(Request $request, Response $response)
    {
        $this->terminateCalled = true;
    }

    public function handle(Request $request, $type = self::MAIN_REQUEST, $catch = true): Response
    {
    }
}
