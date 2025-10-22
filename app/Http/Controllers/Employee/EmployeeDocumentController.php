<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;

class EmployeeDocumentController extends Controller
{
    public function index()
    {
        $documents = EmployeeDocument::where('employee_id', auth()->user()->employee_id)
            ->paginate(10);

        return view('employees.documents.index', compact('documents'));
    }


    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'files' => 'required',
            'files.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png,xlsx,xls|max:10240',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $employeeId = auth()->user()->employee_id; // Get logged-in employee ID
        $uploadedFiles = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Generate unique filename
                $originalFileName = $file->getClientOriginalName();

                // Store file in 'public/employee_documents'
                $filePath = $file->storeAs('employee_documents', $originalFileName, 'public');

                // Save in DB
                $doc = EmployeeDocument::create([
                    'employee_id' => $employeeId,
                    'category' => $request->category,
                    'description' => $request->description ?? null,
                    'file_name' => $originalFileName,
                    'file_path' => 'storage/' . $filePath,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size'   => $file->getSize(),
                ]);

                $uploadedFiles[] = $doc;
            }
        }

        return redirect()->back()->with('success', count($uploadedFiles) . ' file(s) uploaded successfully!');
    }





}
