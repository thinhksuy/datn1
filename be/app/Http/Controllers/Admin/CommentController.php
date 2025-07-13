<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('post', 'user')->get();
        return view('comments.index', compact('comments'));
    }

    public function create()
    {
        $posts = Post::all();
        $users = User::all();
        return view('comments.create', compact('posts', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Post_ID' => 'required|exists:posts,Post_ID',
            'User_ID' => 'required|exists:user,ID',
            'Content' => 'required|string',
            'Status' => 'boolean',
        ]);

        Comment::create($validated);
        return redirect()->route('comments.index')->with('success', 'Comment created successfully.');
    }

    public function show($id)
    {
        $comment = Comment::with('post', 'user')->findOrFail($id);
        return view('comments.show', compact('comment'));
    }

    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        $posts = Post::all();
        $users = User::all();
        return view('comments.edit', compact('comment', 'posts', 'users'));
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $validated = $request->validate([
            'Post_ID' => 'required|exists:posts,Post_ID',
            'User_ID' => 'required|exists:user,ID',
            'Content' => 'required|string',
            'Status' => 'boolean',
        ]);

        $validated['Update_at'] = now();

        $comment->update($validated);
        return redirect()->route('comments.index')->with('success', 'Comment updated successfully.');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return redirect()->route('comments.index')->with('success', 'Comment deleted successfully.');
    }
}
