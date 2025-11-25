<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laptop;
use Illuminate\Http\Request;

class LaptopController extends Controller
{
    public function index(Request $request)
    {
        $query = Laptop::query();

        // --- تطبيق الفلاتر (مثلما كانت في واجهة العميل) ---
        if ($request->filled('hide_expired')) {
            $query->where('quantity', '>', 0);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // ... باقي الفلاتر ...

        $sortField = $request->input('sort', 'name');
        $sortOrder = $request->input('order', 'asc');
        if (in_array($sortField, ['name', 'brand', 'model', 'processor', 'ram', 'storage', 'screen', 'gpu', 'quantity', 'price_numeric'])) {
            $query->orderBy($sortField, $sortOrder);
        } else {
            $query->orderBy('name', $sortOrder); // افتراضي
        }

        $laptops = $query->paginate(500); // عرض 10 أجهزة في كل صفحة

        return view('admin.laptops.index', compact('laptops'));
    }

    public function filter(Request $request)
    {
        $search = $request->search;
        $min = $request->min;
        $max = $request->max;
        $hideFinished = $request->hideFinished == 'true';

        $laptops = Laptop::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%")
                        ->orWhere('brand', 'LIKE', "%$search%")
                        ->orWhere('model', 'LIKE', "%$search%")
                        ->orWhere('processor', 'LIKE', "%$search%")
                        ->orWhere('ram', 'LIKE', "%$search%")
                        ->orWhere('storage', 'LIKE', "%$search%")
                        ->orWhere('screen', 'LIKE', "%$search%")
                        ->orWhere('gpu', 'LIKE', "%$search%");
                });
            })
            ->when($min, fn ($q) => $q->where('price', '>=', $min))
            ->when($max, fn ($q) => $q->where('price', '<=', $max))
            ->when($hideFinished, fn ($q) => $q->where('quantity', '>', 0))
            ->latest()
            ->get();

        return view('admin.laptops.cards', compact('laptops'));
    }

    public function create()
    {
        // صلاحية: create_laptops
        // middleware: permission:create_laptops
        return view('admin.laptops.create');
    }

    public function store(Request $request)
    {
        // صلاحية: create_laptops
        // middleware: permission:create_laptops
        $request->validate([
            'quantity' => 'required|integer|min:0',
            'barcode' => 'required|string|unique:laptops,barcode',
            'name' => 'required|string',
            'price_numeric' => 'required|integer|min:0',
            'price_display' => 'required|string',
            'brand' => 'nullable|string',
            'model' => 'nullable|string',
            'processor' => 'nullable|string',
            'ram' => 'nullable|string',
            'storage' => 'nullable|string',
            'screen' => 'nullable|string',
            'is_touch' => 'boolean',
            'is_convertible' => 'boolean',
            'gpu' => 'nullable|string',
        ]);

        Laptop::create($request->all());

        return redirect()->route('admin.laptops.index')->with('success', 'Laptop added successfully.');
    }

    public function edit(Laptop $laptop)
    {
        // صلاحية: edit_laptops
        // middleware: permission:edit_laptops
        return view('admin.laptops.edit', compact('laptop'));
    }

    public function update(Request $request, Laptop $laptop)
    {
        // صلاحية: edit_laptops
        // middleware: permission:edit_laptops
        $request->validate([
            'quantity' => 'required|integer|min:0',
            'barcode' => 'required|string|unique:laptops,barcode,'.$laptop->id,
            'name' => 'required|string',
            'price_numeric' => 'required|integer|min:0',
            'price_display' => 'required|string',
            'brand' => 'nullable|string',
            'model' => 'nullable|string',
            'processor' => 'nullable|string',
            'ram' => 'nullable|string',
            'storage' => 'nullable|string',
            'screen' => 'nullable|string',
            'is_touch' => 'boolean',
            'is_convertible' => 'boolean',
            'gpu' => 'nullable|string',
        ]);

        $laptop->update($request->all());

        return redirect()->route('admin.laptops.index')->with('success', 'Laptop updated successfully.');
    }

    public function destroy(Laptop $laptop)
    {
        // صلاحية: delete_laptops
        // middleware: permission:delete_laptops
        $laptop->delete(); // soft delete

        return redirect()->route('admin.laptops.index')->with('success', 'Laptop deleted successfully.');
    }
}
