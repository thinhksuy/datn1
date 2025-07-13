<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostCategory;

class PostCategoryApiController extends Controller
{
    public function index()
    {
        return response()->json(PostCategory::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Title' => 'required|string|max:255',
            'Content' => 'nullable|string',
            'Status' => 'boolean',
            'View' => 'nullable|integer',
        ]);

        $category = PostCategory::create($validated);
        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = PostCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($category, 200);
    }

    public function update(Request $request, $id)
    {
        $category = PostCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'Title' => 'required|string|max:255',
            'Content' => 'nullable|string',
            'Status' => 'boolean',
            'View' => 'nullable|integer',
        ]);

        $validated['Updated_at'] = now();

        $category->update($validated);
        return response()->json($category, 200);
    }

    public function destroy($id)
    {
        $category = PostCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }
}
