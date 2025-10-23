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
            ->paginate(6);

        return view('employees.documents.index', compact('documents'));
    }


    public function store(Request $request)
    {
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

                $originalFileName = $file->getClientOriginalName();

                $filePath = $file->storeAs('employee_documents', $originalFileName, 'public');


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
        return redirect()->route('employee.documents')->with('success', count($uploadedFiles) . ' file(s) uploaded successfully!');
    }


    public function download($id)
    {
        $document = EmployeeDocument::findOrFail($id);

        $relativePath = preg_replace('/^storage\//', '', $document->file_path);

        $filePath = storage_path('app/public/' . $relativePath);

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        $fileName = $document->file_name ?? basename($filePath);

        return response()->download($filePath, $fileName);
    }

    public function share($id)
    {

        $document = EmployeeDocument::findOrFail($id);

        $relativePath = preg_replace('/^storage\//', '', $document->file_path);

        $url = asset('storage/' . $relativePath);

        return redirect($url);
    }

}
