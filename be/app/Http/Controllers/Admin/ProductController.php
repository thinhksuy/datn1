<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductAttribute;
use App\Models\ProductValue;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants.values.attribute']);

        if ($request->filled('keyword')) {
            $query->where('Name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('category')) {
            $query->where('Categories_ID', $request->category);
        }

        if ($request->filled('brand')) {
            $query->where('Brand', 'like', '%' . $request->brand . '%');
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('Status', $request->status);
        }

        if ($request->filled('price_min')) {
            $query->where('Price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('Price', '<=', $request->price_max);
        }

        $products = $query->paginate(10)->appends($request->query());
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();

        $weightAttribute = ProductAttribute::firstOrCreate(
            ['Name' => 'Trọng lượng'],
            ['Description' => 'Trọng lượng tiêu chuẩn của vợt cầu lông']
        );

        foreach (['5U', '4U', '3U'] as $value) {
            $weightAttribute->values()->firstOrCreate(['Value' => $value]);
        }

        $attributes = ProductAttribute::with('values')->get();

        return view('admin.products.create', compact('categories', 'attributes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Categories_ID' => 'required|exists:categories,Categories_ID',
            'Name' => 'required|string|max:255',
            'SKU' => 'nullable|string|max:100',
            'Brand' => 'nullable|string|max:255',
            'Description' => 'nullable|string',
            'Image' => 'nullable|image|max:2048',
            'Images.*' => 'nullable|image|max:2048',
            'Price' => 'required|numeric|min:0',
            'Discount_price' => 'nullable|numeric|min:0|lt:Price',
            'Quantity' => 'required|integer|min:0',
            'Status' => 'nullable|boolean',
        ]);

        if ($request->hasFile('Image')) {
            $file = $request->file('Image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $fileName);
            $validated['Image'] = 'uploads/products/' . $fileName;
        }


        $validated['Status'] = $request->has('Status') ? 1 : 0;
        $validated['Created_at'] = now();
        $product = Product::create($validated);

        if ($request->hasFile('Images')) {
            foreach ($request->file('Images') as $img) {
                $fileName = time() . '_' . $img->getClientOriginalName();
                $img->move(public_path('uploads/products/gallery'), $fileName);
                $product->images()->create([
                    'Image_path' => 'uploads/products/gallery/' . $fileName
                ]);
            }
        }



        if ($request->has('variant')) {
            $variantData = $request->input('variant');
            $variantSKU = $variantData['SKU'] ?? 'SKU-' . uniqid();

            $exists = ProductVariant::where('SKU', $variantSKU)->exists();
            if ($exists) {
                return back()->withErrors(['variant.SKU' => 'SKU đã tồn tại.'])->withInput();
            }

            $variant = $product->variants()->create([
                'SKU' => $variantSKU,
                'Variant_name' => $variantData['Variant_name'] ?? '',
                'Price' => $variantData['Price'] ?? 0,
                'Discount_price' => $variantData['Discount_price'] ?? 0,
                'Quantity' => $variantData['Quantity'] ?? 0,
                'Status' => 1,
                'Created_at' => now(),
            ]);

            if (!empty($variantData['Values_IDs'])) {
                $variant->values()->attach($variantData['Values_IDs']);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $attributes = ProductAttribute::with('values')->get();
        $variantSKU = optional($product->variant)->SKU;

        return view('admin.products.edit', compact('product', 'categories', 'attributes', 'variantSKU'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'Categories_ID' => 'required|exists:categories,Categories_ID',
            'Name' => 'required|string|max:255',
            'SKU' => 'nullable|string|max:100',
            'Brand' => 'nullable|string|max:255',
            'Description' => 'nullable|string',
            'Image' => 'nullable|image|max:2048',
            'Images.*' => 'nullable|image|max:2048',
            'Price' => 'required|numeric|min:0',
            'Discount_price' => 'nullable|numeric|min:0|lt:Price',
            'Quantity' => 'required|integer|min:0',
            'Status' => 'nullable|boolean',
        ]);

        if ($request->hasFile('Image')) {
            $file = $request->file('Image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $fileName);
            $validated['Image'] = 'uploads/products/' . $fileName;
        }


        $validated['Status'] = $request->has('Status') ? 1 : 0;
        $validated['Updated_at'] = now();
        $product->update($validated);

        if ($request->hasFile('Images')) {
            foreach ($request->file('Images') as $img) {
                $fileName = time() . '_' . $img->getClientOriginalName();
                $img->move(public_path('uploads/products/gallery'), $fileName);

                $product->images()->create([
                    'Image_path' => 'uploads/products/gallery/' . $fileName
                ]);
            }
        }




        if ($request->has('variant')) {
            $variantData = $request->input('variant');
            $variantSKU = $variantData['SKU'] ?? 'SKU-' . uniqid();
            $variant = $product->variant;

            $duplicate = ProductVariant::where('SKU', $variantSKU)
                ->where('Variant_ID', '!=', optional($variant)->Variant_ID)
                ->exists();

            if ($duplicate) {
                return back()->withErrors(['variant.SKU' => 'SKU đã tồn tại.'])->withInput();
            }

            if ($variant) {
                $variant->update([
                    'SKU' => $variantSKU,
                    'Variant_name' => $variantData['Variant_name'] ?? $variant->Variant_name,
                    'Price' => $variantData['Price'] ?? $variant->Price,
                    'Discount_price' => $variantData['Discount_price'] ?? $variant->Discount_price,
                    'Quantity' => $variantData['Quantity'] ?? $variant->Quantity,
                    'Status' => 1,
                    'Updated_at' => now(),
                ]);
            } else {
                $variant = $product->variants()->create([
                    'SKU' => $variantSKU,
                    'Variant_name' => $variantData['Variant_name'] ?? '',
                    'Price' => $variantData['Price'] ?? 0,
                    'Discount_price' => $variantData['Discount_price'] ?? 0,
                    'Quantity' => $variantData['Quantity'] ?? 0,
                    'Status' => 1,
                    'Created_at' => now(),
                ]);
            }

            if (!empty($variantData['Values_IDs'])) {
                $variant->values()->sync($variantData['Values_IDs']);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy($id)
{
    $product = Product::findOrFail($id);

        if ($product->Image && file_exists(public_path($product->Image))) {
                unlink(public_path($product->Image));
        }

        foreach ($product->images as $image) {
            if ($image->Image_path && file_exists(public_path($image->Image_path))) {
                unlink(public_path($image->Image_path));
            }
            $image->delete();
        }


    $product->delete();

    return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
}


}
