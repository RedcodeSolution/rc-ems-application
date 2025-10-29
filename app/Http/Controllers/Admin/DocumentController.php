<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Document;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::latest()->paginate(10);
        $departments = Department::all();

        return view('admin.documents.index', compact('documents', 'departments'));
    }

    public function getProjectsByDepartment($departmentId)
    {
        $projects = Project::whereHas('team', function ($q) use ($departmentId) {
            $q->where('department_id', $departmentId);
        })->select('project_id', 'project_name')->get();

        return response()->json([
            'success' => true,
            'projects' => $projects
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'category'      => 'required|string|max:100',
            'department_id' => 'nullable|exists:departments,department_id',
            'project_id'    => 'nullable|exists:projects,project_id',
            'access_level'  => 'required|in:public,department,admin,restricted',
            'tags'          => 'nullable|string',
            'file'          => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
            'notify_users'  => 'nullable|boolean',
        ]);

        $lastDocument = Document::orderBy('document_id', 'desc')->first();
        if ($lastDocument) {
            $lastId = intval(substr($lastDocument->document_id, 3));
            $newId = 'DOC' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newId = 'DOC001';
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $filename, 'public');
        } else {
            return back()->withErrors(['file' => 'File upload failed']);
        }

        Document::create([
            'document_id'   => $newId,
            'title'         => $request->title,
            'description'   => $request->description,
            'category'      => $request->category,
            'department_id' => $request->department_id,
            'project_id'    => $request->project_id,
            'access_level'  => $request->access_level,
            'tags'          => $request->tags,
            'file_path'     => $path,
            'notify_users'  => $request->has('notify_users') ? true : false,
        ]);

        return redirect()->route('admin.documents.index')->with('success', 'Document uploaded successfully!');
        }

    public function edit($document_id)
    {
        $document = Document::with(['department', 'project'])->findOrFail($document_id);
        $departments = Department::all();
        $projects = Project::all();

        return response()->json([
            'success' => true,
            'document' => $document,
            'departments' => $departments,
            'projects' => $projects
        ]);
    }


    public function update(Request $request, $document_id)
    {
        $document = Document::findOrFail($document_id);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'category'      => 'required|string|max:100',
            'department_id' => 'nullable|exists:departments,department_id',
            'project_id'    => 'nullable|exists:projects,project_id',
            'access_level'  => 'required|in:public,department,admin,restricted',
            'tags'          => 'nullable|string',
            'file'          => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
            'notify_users'  => 'nullable|boolean',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('documents', $filename, 'public');

            // Delete old file if it exists
            if ($document->file_path && \Storage::disk('public')->exists($document->file_path)) {
                \Storage::disk('public')->delete($document->file_path);
            }

            $validated['file_path'] = $path;
        }

        $document->update([
            'title'         => $validated['title'],
            'description'   => $validated['description'] ?? $document->description,
            'category'      => $validated['category'],
            'department_id' => $validated['department_id'] ?? null,
            'project_id'    => $validated['project_id'] ?? null,
            'access_level'  => $validated['access_level'],
            'tags'          => $validated['tags'] ?? null,
            'file_path'     => $validated['file_path'] ?? $document->file_path,
            'notify_users'  => $request->has('notify_users') ? true : false,
        ]);

        if ($request->has('notify_users')) {
        }

        if ($request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Document updated successfully!',
                'document' => $document->fresh(['department']),
            ]);
        }
        return redirect()
            ->route('admin.documents.index')
            ->with('success', 'Document updated successfully!');
    }


    public function show($document_id)
    {
        $document = Document::with('department')->where('document_id', $document_id)->firstOrFail();

        return response()->json([
            'document' => [
                'id'         => $document->id,
                'document_id' => $document->document_id,
                'title'      => $document->title,
                'description' => $document->description,
                'category'   => $document->category,
                'department' => $document->department->department_name ?? 'N/A',
                'access'     => $document->access_level,
                'tags'       => $document->tags,
                'uploader'   => $document->department->department_name ?? '',
                'date'       => $document->created_at->format('M d, Y'),
                'downloads'  => $document->downloads ?? 0,
                'views'      => $document->views ?? 0,
                'file_path'  => $document->file_path,

                'size' => !empty($document->file_path) && file_exists(storage_path('app/public/' . $document->file_path))
                    ? filesize(storage_path('app/public/' . $document->file_path))
                    : 0,
            ]
        ]);
    }

    public function destroy($document_id)
    {
        $document = Document::where('document_id', $document_id)->first();

        if (!$document) {
            return redirect()->route('admin.documents.index')
                ->with('error', 'Document not found.');
        }

        if ($document->file_path && Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('admin.documents.index')->with('success', 'Document deleted successfully.');
    }

    public function incrementDownload($document_id)
    {
        $document = Document::where('document_id', $document_id)->firstOrFail();

        $document->increment('downloads');

        $totalDownloads = Document::sum('downloads');

        return response()->json([
            'downloads' => $document->downloads,
            'total' => $totalDownloads,
        ]);
    }

    public function download($document_id)
    {
        $document = Document::where('document_id', $document_id)->firstOrFail();

        $filePath = storage_path('app/public/' . $document->file_path);
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        $filename = $document->title . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
        return response()->download($filePath, $filename);
    }
}
