<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\MonthlyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class MonthlyReportController extends Controller
{
    public function userIndex()
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        $reports = collect();

        if ($employee) {
            $reports = MonthlyReport::where('emp_id', $employee->id)
                ->orderBy('report_month', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('user.monthly-report')->with([
            'employee' => $employee,
            'reports' => $reports,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'monthly_report' => 'required|file|mimes:pdf|max:5120|mimetypes:application/pdf',
            'report_month' => 'required|date_format:Y-m',
        ]);

        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            $employee = new Employee();
            $employee->name = $user->name;
            $employee->email = $user->email;
            $employee->position = 'User';
            $employee->save();
        }

        $file = $request->file('monthly_report');
        $timestamp = now()->format('YmdHis');
        $extension = $file->getClientOriginalExtension();
        $month = $request->report_month;
        $safeName = 'monthly-report-' . $employee->id . '-' . $timestamp . '.' . $extension;
        $path = $file->storeAs('monthly-reports/' . $month, $safeName, 'public');

        $report = new MonthlyReport();
        $report->emp_id = $employee->id;
        $report->uploaded_by = $user->id;
        $report->report_month = $month;
        $report->file_name = $file->getClientOriginalName();
        $report->file_path = $path;
        $report->file_size = $file->getSize();
        $report->mime_type = $file->getClientMimeType();
        $report->save();

        flash()->success('Berhasil', 'Monthly report berhasil diunggah.');
        return redirect()->route('user.monthly-report');
    }

    public function index()
    {
        $reports = MonthlyReport::with(['employee', 'uploadedBy'])
            ->orderBy('report_month', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.monthly-report')->with(['reports' => $reports]);
    }

    public function download(MonthlyReport $monthlyReport)
    {
        $user = auth()->user();
        $isAdmin = $user && $user->hasRole('admin');

        if (!$isAdmin) {
            $employee = Employee::where('email', $user->email)->first();
            if (!$employee || $employee->id !== $monthlyReport->emp_id) {
                abort(403);
            }
        }

        if (!Storage::disk('public')->exists($monthlyReport->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($monthlyReport->file_path, $monthlyReport->file_name);
    }

    public function exportMonthZip(string $month)
    {
        $reports = MonthlyReport::with(['employee'])
            ->where('report_month', $month)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($reports->isEmpty()) {
            flash()->error('Gagal', 'Tidak ada file pada bulan tersebut.');
            return redirect()->route('monthly-report.index');
        }

        $tmpDir = storage_path('app/tmp');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $zipName = 'monthly-report-' . $month . '.zip';
        $zipPath = $tmpDir . DIRECTORY_SEPARATOR . $zipName;
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            flash()->error('Gagal', 'Gagal membuat file ZIP.');
            return redirect()->route('monthly-report.index');
        }

        foreach ($reports as $report) {
            if (!Storage::disk('public')->exists($report->file_path)) {
                continue;
            }
            $fullPath = Storage::disk('public')->path($report->file_path);
            $empId = optional($report->employee)->id ?? 'unknown';
            $empName = optional($report->employee)->name ?? 'unknown';
            $filename = $empId . '-' . preg_replace('/[^a-zA-Z0-9_\-]+/', '-', $empName) . '-' . $report->file_name;
            $zip->addFile($fullPath, $filename);
        }

        $zip->close();

        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }
}
