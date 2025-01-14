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
        ->orderBy('updated_by', 'desc')
        ->get();
        
        $employees->each( fn($employee) => $employee->append('full_name'));
        
        return EmployeeResource::collection($employees);
    }

    public function store(EmployeeRequest $request)
    {
        $employee = Employee::create([
            'first_name' => $request->validated('first_name'),
            'last_name' => $request->validated('last_name'),
            'email'=> $request->validated('email'),
            'phone'=> $request->validated('phone'),
            'salary'=> $request->validated('salary'),
            'status'=> $request->validated('status'),
            'cin_number'=> $request->validated('cin_number'),
            'cnss_number'=> $request->validated('cnss_number'),
            'joining_date'=> $request->validated('joining_date'),
            'position'=> $request->validated('position'),
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

        $employee->append('full_name');

        return new EmployeeResource($employee);
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $employee->update([
            'first_name' => $request->validated('first_name'),
            'last_name' => $request->validated('last_name'),
            'email'=> $request->validated('email'),
            'phone'=> $request->validated('phone'),
            'salary'=> $request->validated('salary'),
            'status'=> $request->validated('status'),
            'cin_number'=> $request->validated('cin_number'),
            'cnss_number'=> $request->validated('cnss_number'),
            'joining_date'=> $request->validated('joining_date'),
            'position'=> $request->validated('position'),
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
