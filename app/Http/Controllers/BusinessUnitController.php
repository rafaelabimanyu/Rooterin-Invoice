<?php

namespace App\Http\Controllers;

use App\Models\BusinessUnit;
use Illuminate\Http\Request;

class BusinessUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BusinessUnit::withCount('invoices');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $businessUnits = $query->orderBy('name')->paginate(10);

        return view('business-units.index', compact('businessUnits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('business-units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:business_units,name',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        BusinessUnit::create($validated);

        return redirect()->route('business-units.index')
            ->with('success', app()->getLocale() == 'en' ? 'Business Unit created successfully.' : 'Unit Bisnis berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BusinessUnit $businessUnit)
    {
        return redirect()->route('business-units.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BusinessUnit $businessUnit)
    {
        return view('business-units.edit', compact('businessUnit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusinessUnit $businessUnit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:business_units,name,' . $businessUnit->id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        $businessUnit->update($validated);

        return redirect()->route('business-units.index')
            ->with('success', app()->getLocale() == 'en' ? 'Business Unit updated successfully.' : 'Unit Bisnis berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusinessUnit $businessUnit)
    {
        if ($businessUnit->invoices()->exists()) {
            return redirect()->back()
                ->with('danger', app()->getLocale() == 'en' 
                    ? 'Cannot delete Business Unit because it has active invoice transactions.' 
                    : 'Unit Bisnis tidak dapat dihapus karena memiliki transaksi invoice aktif.');
        }

        $businessUnit->delete();

        return redirect()->route('business-units.index')
            ->with('success', app()->getLocale() == 'en' ? 'Business Unit deleted successfully.' : 'Unit Bisnis berhasil dihapus.');
    }
}
