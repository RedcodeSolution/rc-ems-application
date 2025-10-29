<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Mail\ShareDocumentMail;
use App\Models\Document;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmployeeDocumentController extends Controller
{
    public function index()
    {
        $employeeId = Auth::user()?->employee_id;


        $documents = EmployeeDocument::where('employee_id', auth()->user()->employee_id)
            ->paginate(6);

        if (!$employeeId) {
            $employeeDocuments = collect(); // empty collection
        } else {
            // Fetch documents assigned to this employee with department and project info
            $employeeDocuments = DB::table('documents')
                ->join('projects', 'documents.project_id', '=', 'projects.project_id')
                ->join('employee_project', 'projects.project_id', '=', 'employee_project.project_id')
                ->leftJoin('departments', 'documents.department_id', '=', 'departments.department_id')
                ->select(
                    'documents.document_id',
                    'documents.title',
                    'documents.description',
                    'documents.category',
                    'documents.department_id',
                    'departments.department_name',
                    'documents.project_id',
                    'projects.project_name',
                    'documents.file_path',
                    'documents.access_level',
                    'documents.downloads',
                    'documents.created_at'
                )
                ->where('employee_project.employee_id', $employeeId)
                ->get();

        }

        return view('employees.documents.index', compact('documents', 'employeeDocuments'));
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
        $document = DB::table('documents')->where('document_id', $id)->first();

        if (!$document) {
            return back()->with('error', 'Document not found.');
        }

        $filePath = storage_path('app/public/' . preg_replace('/^storage\//', '', $document->file_path));

        if (!file_exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        return response()->download($filePath, basename($filePath));
    }

    // Download employee's personal document
    public function downloadEmployeeDocument($id)
    {
        $employeeDoc = EmployeeDocument::find($id);

        if (!$employeeDoc) {
            return back()->with('error', 'Employee document not found.');
        }

        $filePath = storage_path('app/public/' . preg_replace('/^storage\//', '', $employeeDoc->file_path));

        if (!file_exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        return response()->download($filePath, basename($filePath));
    }

    public function share($id)
    {
        $document = EmployeeDocument::findOrFail($id);
        $userEmail = auth()->user()->email; // recipient email

        Mail::to($userEmail)->send(new ShareDocumentMail($document));

        return back()->with('success', 'Document sent successfully!');
    }


    public function shareCompany($id)
    {
        // Find the document by ID
        $document = DB::table('documents')->where('document_id', $id)->first();

        if (!$document) {
            return back()->with('error', 'Document not found.');
        }

        $filePath = storage_path('app/public/' . preg_replace('/^storage\//', '', $document->file_path));

        if (!file_exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        $employeeEmail = auth()->user()->email;

        Mail::send('emails.share-document', ['document' => $document], function ($message) use ($employeeEmail, $document, $filePath) {
            $message->to($employeeEmail)
                ->subject('📄 Shared Company Document: ' . $document->title)
                ->attach($filePath, [
                    'as' => basename($filePath),
                    'mime' => mime_content_type($filePath),
                ]);
        });

        return back()->with('success', 'Document sent successfully to your email!');
    }



}
