<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::query()
        ->with([
            'createdBy:id,name',
            'updatedBy:id,name',
        ])
        ->get();
        
        
        return EmployeeResource::collection($employees);
    }

    public function store(EmployeeRequest $request)
    {
        $employee = Employee::create([
            'full_name' => $request->validated('full_name'),
            'email'=> $request->validated('email'),
            'phone'=> $request->validated('phone'),
            'role'=> $request->validated('role'),
            'salary'=> $request->validated('salary'),
            'status'=> $request->validated('status'),
            'created_by' => auth()->id(),
        ]);

        return new EmployeeResource($employee);
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'createdBy:id,name',
            'updatedBy:id,name',
        ]);

        return new EmployeeResource($employee);
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $employee->update([
            'full_name' => $request->validated('full_name'),
            'email'=> $request->validated('email'),
            'phone'=> $request->validated('phone'),
            'role'=> $request->validated('role'),
            'salary'=> $request->validated('salary'),
            'status'=> $request->validated('status'),
            'updated_by' => auth()->id(),
        ]);

        return new EmployeeResource($employee);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->noContent();
    }

}
