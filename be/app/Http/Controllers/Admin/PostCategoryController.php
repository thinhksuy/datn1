<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    public function index()
    {
        $categories = PostCategory::all();
        return view('admin.post_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.post_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Title' => 'required|string|max:255',
            'Content' => 'nullable|string',
            'Status' => 'boolean',
            'View' => 'nullable|integer',
        ]);

        PostCategory::create($validated);
        return redirect()->route('admin.post_categories.index')->with('success', 'Created successfully');
    }

    public function show($id)
    {
        $category = PostCategory::findOrFail($id);
        return view('admin.post_categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = PostCategory::findOrFail($id);
        return view('admin.post_categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = PostCategory::findOrFail($id);

        $validated = $request->validate([
            'Title' => 'required|string|max:255',
            'Content' => 'nullable|string',
            'Status' => 'boolean',
            'View' => 'nullable|integer',
        ]);

        $validated['Updated_at'] = now();

        $category->update($validated);
        return redirect()->route('admin.post_categories.index')->with('success', 'Updated successfully');
    }

    public function destroy($id)
    {
        $category = PostCategory::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.post_categories.index')->with('success', 'Deleted successfully');
    }
}
