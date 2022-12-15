<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::all();
        $departments = Department::all();

        return view('employee.index', compact(
            'employees',
            'departments'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        $departments = Department::all();
        
        return view('employee.edit', compact(
            'employee',
            'departments'
        ));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Employee  $employee
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'forename' => 'string|required',
            'surname' => 'string|required',
            'department_id' => 'int|required',
            'status' => 'string|required'
        ]);


        $employee->update([
            'forename' => $request->post('forename'),
            'surname' => $request->post('surname'),
            'department_id' => $request->post('department_id'),
            'status' => $request->post('status')
        ]);

        return redirect()->back()->with('success', 'Employee has been updated.');
    }

}
