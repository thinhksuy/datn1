<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user', 'category')->get();
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $users = User::whereIn('Role_ID', [1, 2])->get();
        $categories = PostCategory::all();
        return view('admin.posts.create', compact('users', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'User_ID' => 'required|exists:user,ID',
            'Category_ID' => 'required|exists:post_categories,Post_Categories_ID',
            'Title' => 'required|string|max:255',
            'Thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Content' => 'required',
            'Excerpt' => 'nullable|string',
            'Status' => 'boolean',
            'View' => 'nullable|integer',
        ]);

        if ($request->hasFile('Thumbnail')) {
            $imagePath = $request->file('Thumbnail')->store('uploads/admin/posts', 'public');
            $validated['Thumbnail'] = '/storage/' . $imagePath;
        }

        Post::create($validated);
        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    public function show($id)
    {
        $post = Post::with('user', 'category')->findOrFail($id);
        return view('admin.posts.show', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $users = User::whereIn('Role_ID', [1, 2])->get();
        $categories = PostCategory::all();
        return view('admin.posts.edit', compact('post', 'users', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'User_ID' => 'required|exists:user,ID',
            'Category_ID' => 'required|exists:post_categories,Post_Categories_ID',
            'Title' => 'required|string|max:255',
            'Thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Content' => 'required',
            'Excerpt' => 'nullable|string',
            'Status' => 'boolean',
            'View' => 'nullable|integer',
        ]);

        if ($request->hasFile('Thumbnail')) {
            $imagePath = $request->file('Thumbnail')->store('uploads/admin/posts', 'public');
            $validated['Thumbnail'] = '/storage/' . $imagePath;
        }

        $validated['Updated_at'] = now();

        $post->update($validated);
        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }
}
