<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\FinalReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class FinalReportController extends Controller
{
    public function userIndex()
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        $reports = collect();
        $lastFinalReport = null;

        if ($employee) {
            $reports = FinalReport::where('emp_id', $employee->id)
                ->orderBy('created_at', 'desc')
                ->get();
            $lastFinalReport = $reports->first();
        }

        return view('user.final-report')->with([
            'employee' => $employee,
            'reports' => $reports,
            'lastFinalReport' => $lastFinalReport,
        ]);
    }

    public function index()
    {
        $reports = FinalReport::with(['employee', 'uploadedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.final-report')->with(['reports' => $reports]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'final_report' => 'required|file|mimes:pdf|max:10240|mimetypes:application/pdf',
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

        $file = $request->file('final_report');
        $timestamp = now()->format('YmdHis');
        $extension = $file->getClientOriginalExtension();
        $safeName = 'final-report-' . $employee->id . '-' . $timestamp . '.' . $extension;
        $month = now()->format('Y-m');
        $path = $file->storeAs('final-reports/' . $month, $safeName, 'public');

        $report = new FinalReport();
        $report->emp_id = $employee->id;
        $report->uploaded_by = $user->id;
        $report->file_name = $file->getClientOriginalName();
        $report->file_path = $path;
        $report->file_size = $file->getSize();
        $report->mime_type = $file->getClientMimeType();
        $report->save();

        flash()->success('Berhasil', 'Final report berhasil diunggah.');
        return redirect()->route('user.final-report');
    }

    public function download(FinalReport $finalReport)
    {
        $user = auth()->user();
        $isAdmin = $user && $user->hasRole('admin');

        if (!$isAdmin) {
            $employee = Employee::where('email', $user->email)->first();
            if (!$employee || $employee->id !== $finalReport->emp_id) {
                abort(403);
            }
        }

        if (!Storage::disk('public')->exists($finalReport->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($finalReport->file_path, $finalReport->file_name);
    }

    public function exportMonthZip(string $month)
    {
        $reports = FinalReport::with(['employee'])
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$month])
            ->orderBy('created_at', 'asc')
            ->get();

        if ($reports->isEmpty()) {
            flash()->error('Gagal', 'Tidak ada file pada bulan tersebut.');
            return redirect()->route('final-report.index');
        }

        $tmpDir = storage_path('app/tmp');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $zipName = 'final-report-' . $month . '.zip';
        $zipPath = $tmpDir . DIRECTORY_SEPARATOR . $zipName;
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            flash()->error('Gagal', 'Gagal membuat file ZIP.');
            return redirect()->route('final-report.index');
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
