<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::latest()->paginate(10);
        $departments = Department::all();
        $employees = Employee::all();
        $projects = Project::all();
        return view('admin.reports.index', compact('reports','departments', 'employees', 'projects'));
    }

    public function store(Request $request)
    {
         $request->validate([
            'report_name'           => 'required|string|max:255',
            'report_type'           => 'required|string|max:100',
            'start_date'            => 'required|date',
            'end_date'              => 'required|date|after_or_equal:start_date',
            'employee_id'           => 'nullable|exists:employees,employee_id',
            'department_id'         => 'nullable|exists:departments,department_id',
            'report_format'         => 'required|string',
            'priority'              => 'required|string',
            'email'                 => 'nullable|string',
            'description'           => 'nullable|string',
            'special_instructions'  => 'nullable|string',

        ]);


        $lastReport = Report::orderBy('report_id', 'desc')->first();

        if ($lastReport) {
            // Extract numeric part after 'RPT'
            $lastId = intval(substr($lastReport->report_id, 3));
            $newId = 'RPT' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newId = 'RPT001';
        }

        $request['report_id'] = $newId;

        Report::create($request->all());

        return redirect()->route('admin.reports.index')->with('success', 'Report created successfully!');
    }


    public function download($reportId)
    {
        $report = Report::with(['department', 'employee'])->findOrFail($reportId);

        return Pdf::loadView('admin.reports.pdf', [
            'report' => $report,
            'department' => $report->department->department_name ?? '-',
            'employee' => $report->employee->employee_name ?? '-',
        ])->download($report->report_name.'.pdf');
    }

    public function show($reportId)
    {
        $report = Report::where('report_id', $reportId)->firstOrFail();

        $department = optional($report->department)->department_name ?? '-';
        $employee   = optional($report->employee)->employee_name ?? '-';

        $pdf = Pdf::loadView('admin.reports.show', [
            'report'     => $report,
            'department' => $department,
            'employee'   => $employee,
        ]);

        return $pdf->stream($report->report_name . '.pdf');
    }



}
