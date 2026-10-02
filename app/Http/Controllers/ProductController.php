<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->paginate(12);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180', 'unique:products,name'],
            // client requirement: product image must be uploaded
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $path = $request->file('image')->store('products', 'public');

        $product = Product::create([
            'name' => $data['name'],
            'image_path' => $path,
        ]);

        return redirect()->route('products.show', $product)->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['materials.unit', 'materials.components.childMaterial.unit']);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180', 'unique:products,name,' . $product->id],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }
            $product->image_path = $request->file('image')->store('products', 'public');
        }

        $product->name = $data['name'];
        $product->save();

        return redirect()->route('products.show', $product)->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        try {
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            $product->delete();

            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('products.index')->with('error', 'Cannot delete this product because it is referenced by orders.');
        }
    }
}
