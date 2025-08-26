<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


class DepartmentController extends Controller
{
    public function index()
    {
        $departments= Department::all();
        return view('admin.departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_head' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|in:Active,Inactive',
        ]);

        Department::create($request->all());

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully!');

    }


    public function edit($id)
    {
        try {
            $department = Department::find($id);
            // Return JSON data for the modal
            return response()->json([
                'success' => true,
                'department' => $department
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found'
            ], 404);
        }
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'department_name'  => 'required|string|max:255',
            'description'      => 'nullable|string',
            'department_head'  => 'nullable|string|max:255',
            'location'         => 'nullable|string|max:255',
            'phone'            => 'nullable|string|max:50',
            'email'            => 'nullable|email|max:255',
            'budget'           => 'nullable|numeric|min:0',
            'status'           => 'required|in:Active,Inactive',
        ]);

        $department = Department::findOrFail($id);
        $department->update($validated);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully!');
    }

    public function show($id)
    {
        try {
            $department = Department::findOrFail($id);

            return response()->json([
                'success' => true,
                'department' => $department
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deleted successfully!');
    }



}
