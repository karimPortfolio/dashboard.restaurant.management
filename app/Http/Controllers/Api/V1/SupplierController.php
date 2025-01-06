<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::query()
        ->with([
            'createdBy:id,name',
        ])
        ->get();

        return response()->json($suppliers);
    }

    public function store(SupplierRequest $request)
    {
        $supplier = Supplier::create([
            'name'=> $request->validated('name'),
            'address'=> $request->validated('address'),
            'phone'=> $request->validated('phone'),
            'email'=> $request->validated('email'),
            'notes'=> $request->validated('notes'),
            'created_by'=> auth()->user()->id,
        ]);

        return SupplierResource::collection($supplier);
    }


    public function show(Supplier $supplier)
    {
        $supplier->load([
            'createdBy:id,name',
        ]);

        return SupplierResource::make($supplier);
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $supplier->update([
            'name'=> $request->validated('name'),
            'address'=> $request->validated('address'),
            'phone'=> $request->validated('phone'),
            'email'=> $request->validated('email'),
            'notes'=> $request->validated('notes'),
        ]);

        return SupplierResource::make($supplier);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response()->noContent();
    }
}
