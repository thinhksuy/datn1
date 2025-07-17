    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Admin\CategoryController;
    use App\Http\Controllers\Admin\ProductController;
    use App\Http\Controllers\Admin\UserController;
    use App\Http\Controllers\Admin\RoleController;
    use App\Http\Controllers\Admin\ProductAttributeController;
    use App\Http\Controllers\Admin\ProductVariantController;
    use App\Http\Controllers\Admin\ProductValueController;
    use App\Http\Controllers\Admin\ProductVariantValueController;
    use App\Http\Controllers\Admin\CategoryAttributeController;
    use App\Http\Controllers\Admin\OrderController;
    use App\Http\Controllers\Admin\OrderDetailController;
    use App\Http\Controllers\Admin\ProductStatisticsController;
    use App\Http\Controllers\Admin\DashboardController;
    use App\Http\Controllers\Admin\CourtController;
    use App\Http\Controllers\Admin\CourtBookingController;
    use App\Http\Controllers\Admin\VoucherController;
    use App\Http\Controllers\Admin\PostCategoryController;
    use App\Http\Controllers\Admin\PostController;
    use App\Http\Controllers\Admin\PostCommentController;
    use App\Http\Controllers\Admin\ProductReviewController;
    use App\Http\Controllers\Admin\StatisticsController;










    // Route::get('/', function () {
    //     return view('welcome');
    // });

    // ✅ Trang dashboard admin (hiển thị thống kê)
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    // ✅ Nhóm route ADMIN
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/orders/statistics', [OrderController::class, 'statistics'])->name('orders.statistics');
        Route::resource('orders', OrderController::class);
        Route::delete('order-details/{id}', [OrderDetailController::class, 'destroy'])->name('order-details.destroy');

        Route::get('/product-statistics', [ProductStatisticsController::class, 'index'])->name('product-statistics');

        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('product_attributes', ProductAttributeController::class);
        Route::resource('variants', ProductVariantController::class);
        Route::resource('product_values', ProductValueController::class);
        Route::resource('product_variant_values', ProductVariantValueController::class);

            Route::resource('users', UserController::class);

        Route::resource('roles', RoleController::class);

        Route::get('category-attribute/create', [CategoryAttributeController::class, 'create'])->name('category-attribute.create');
        Route::post('category-attribute/store', [CategoryAttributeController::class, 'store'])->name('category-attribute.store');

        Route::get('comments', fn () => view('admin.comments.index'))->name('comments.index');

        Route::resource('courts', CourtController::class);
        Route::resource('bookings', CourtBookingController::class);

    Route::resource('vouchers', VoucherController::class);


    Route::resource('posts', PostController::class);

    Route::resource('post_categories', PostCategoryController::class);
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::resource('post', PostCommentController::class);
        Route::resource('product', ProductReviewController::class);
        Route::patch('product/{id}/toggle-status', [ProductReviewController::class, 'toggleStatus'])->name('product.toggleStatus');
        Route::patch('post/{id}/update-status', [PostCommentController::class, 'updateStatus'])->name('post.updateStatus');
    });


    Route::prefix('statistics')->name('statistics.')->group(function () {
        Route::get('revenue', [StatisticsController::class, 'indexrevenue'])->name('revenue');
        Route::get('order', [StatisticsController::class, 'indexorder'])->name('order');
        Route::get('booking', [StatisticsController::class, 'indexbooking'])->name('booking');
        Route::get('product', [StatisticsController::class, 'indexproduct'])->name('product');
    });




    });
