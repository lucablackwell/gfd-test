<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

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
        // add departments later
        return view('employee.index', [
            'employees' => $employees
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        return view('employee.edit', compact(
            'employee'
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
            'department' => 'int|required',
        ]);


        $employee->update([
            'forename' => $request->post('forename'),
            'surname' => $request->post('surname'),
            'department' => $request->post('department'),
            'active' => is_null($request->post('active')) ? false : true
        ]);

        return redirect()->back()->with('success', 'Employee has been updated.');
    }

}
