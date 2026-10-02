<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::with('unit')->orderBy('name');

        if ($request->get('filter') === 'low') {
            // low-stock = stock <= threshold
            $query->whereColumn('stock', '<=', 'threshold');
        }

        $materials = $query->paginate(15)->withQueryString();

        $lowCount = Material::whereColumn('stock', '<=', 'threshold')->count();

        return view('materials.index', compact('materials', 'lowCount'));
    }

    public function create()
    {
        $units = Unit::orderBy('name')->get();

        return view('materials.create', compact('units'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:materials,name'],
            'unit_id' => ['required', 'exists:units,id'],
            'stock' => ['required', 'numeric', 'min:0'],
            'threshold' => ['required', 'numeric', 'min:0'],
            'is_composite' => ['nullable', 'boolean'],

            // Additional client requirement: images must be uploaded
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data['is_composite'] = (bool) ($data['is_composite'] ?? false);

        // Save image
        $path = $request->file('image')->store('materials', 'public');
        $data['image_path'] = $path;

        Material::create($data);

        return redirect()->route('materials.index')->with('success', 'Material created successfully.');
    }

    public function show(Material $material)
    {
        $material->load(['unit', 'components.childMaterial.unit']);

        return view('materials.show', compact('material'));
    }

    public function edit(Material $material)
    {
        $units = Unit::orderBy('name')->get();

        return view('materials.edit', compact('material', 'units'));
    }

    public function update(Request $request, Material $material)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:materials,name,' . $material->id],
            'unit_id' => ['required', 'exists:units,id'],
            'stock' => ['required', 'numeric', 'min:0'],
            'threshold' => ['required', 'numeric', 'min:0'],
            'is_composite' => ['nullable', 'boolean'],

            // update image optional
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data['is_composite'] = (bool) ($data['is_composite'] ?? false);

        // Replace image if provided
        if ($request->hasFile('image')) {
            if ($material->image_path && Storage::disk('public')->exists($material->image_path)) {
                Storage::disk('public')->delete($material->image_path);
            }
            $data['image_path'] = $request->file('image')->store('materials', 'public');
        }

        $material->update($data);

        return redirect()->route('materials.show', $material)->with('success', 'Material updated successfully.');
    }

    public function destroy(Material $material)
    {
        try {
            if ($material->image_path && Storage::disk('public')->exists($material->image_path)) {
                Storage::disk('public')->delete($material->image_path);
            }

            $material->delete();

            return redirect()->route('materials.index')->with('success', 'Material deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('materials.index')->with('error', 'Cannot delete this material because it is referenced by products/composites/orders.');
        }
    }
}
