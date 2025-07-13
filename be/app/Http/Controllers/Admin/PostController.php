<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user', 'category')->get();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $users = User::all();
        $categories = PostCategory::all();
        return view('posts.create', compact('users', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'User_ID' => 'required|exists:user,ID',
            'Category_ID' => 'required|exists:post_categories,Post_Categories_ID',
            'Title' => 'required|string|max:255',
            'Thumbnail' => 'nullable|string',
            'Content' => 'required',
            'Excerpt' => 'nullable|string',
            'Status' => 'boolean',
            'View' => 'nullable|integer',
        ]);

        Post::create($validated);
        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function show($id)
    {
        $post = Post::with('user', 'category')->findOrFail($id);
        return view('posts.show', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $users = User::all();
        $categories = PostCategory::all();
        return view('posts.edit', compact('post', 'users', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'User_ID' => 'required|exists:user,ID',
            'Category_ID' => 'required|exists:post_categories,Post_Categories_ID',
            'Title' => 'required|string|max:255',
            'Thumbnail' => 'nullable|string',
            'Content' => 'required',
            'Excerpt' => 'nullable|string',
            'Status' => 'boolean',
            'View' => 'nullable|integer',
        ]);

        $validated['Updated_at'] = now();

        $post->update($validated);
        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
