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
        $posts = Post::with('user', 'category')->paginate(10);
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
            $image = $request->file('Thumbnail');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/posts'), $imageName);
            $validated['Thumbnail'] = 'uploads/posts/' . $imageName;
        }


        Post::create($validated);
        return redirect()->route('admin.posts.index')->with('success', 'Tạo bài viết thành công!');
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
            $image = $request->file('Thumbnail');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/posts'), $imageName);
            $validated['Thumbnail'] = 'uploads/posts/' . $imageName;
        }



        $validated['Updated_at'] = now();

        $post->update($validated);
        return redirect()->route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy($id)
{
    $post = Post::findOrFail($id);

    // Xoá ảnh nếu tồn tại
    if ($post->Thumbnail && file_exists(public_path($post->Thumbnail))) {
        unlink(public_path($post->Thumbnail));
    }

    $post->delete();

    return redirect()->route('admin.posts.index')->with('success', 'Xóa bài viết thành công!');
}

}
