<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentApiController extends Controller
{
    public function index()
    {
        return response()->json(Comment::with('post', 'user')->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Post_ID' => 'required|exists:posts,Post_ID',
            'User_ID' => 'required|exists:user,ID',
            'Content' => 'required|string',
            'Status' => 'boolean',
        ]);

        $comment = Comment::create($validated);
        return response()->json($comment, 201);
    }

    public function show($id)
    {
        $comment = Comment::with('post', 'user')->find($id);
        if (!$comment) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($comment, 200);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'Post_ID' => 'required|exists:posts,Post_ID',
            'User_ID' => 'required|exists:user,ID',
            'Content' => 'required|string',
            'Status' => 'boolean',
        ]);

        $validated['Update_at'] = now();

        $comment->update($validated);
        return response()->json($comment, 200);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $comment->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }
}
