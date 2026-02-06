<?php

namespace App\Http\Controllers;

use App\Imports\ImportErrorBag;
use App\Imports\ImportStats;
use App\Imports\MasterImport;
use App\Exports\TemplateExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function index(): View
    {
        return view('import.index');
    }

    public function preview(Request $request): View
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $storedPath = $file->storeAs('import-temp', uniqid('import_', true) . '.' . $file->getClientOriginalExtension());

        $errors = new ImportErrorBag();
        $stats = new ImportStats();
        $import = new MasterImport($errors, $stats, true);

        Excel::import($import, Storage::disk('local')->path($storedPath));

        $errorFile = null;
        if ($errors->has()) {
            $errorFile = 'import-errors/import-errors-' . now()->format('Ymd-His') . '.txt';
            Storage::disk('local')->put($errorFile, $errors->toText());
        }

        return view('import.preview', [
            'stats' => $stats->all(),
            'errors' => $errors->all(),
            'error_counts' => $errors->counts(),
            'samples' => $stats->samples(),
            'stored_file' => $storedPath,
            'error_file' => $errorFile,
            'preview_json' => json_encode([
                'stats' => $stats->all(),
                'errors' => $errors->all(),
                'error_counts' => $errors->counts(),
                'samples' => $stats->samples(),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'stored_file' => 'required|string',
            'error_file' => 'nullable|string',
        ]);

        $storedFile = $request->input('stored_file');
        if (!Storage::disk('local')->exists($storedFile)) {
            return redirect()->back()->with('error', 'File import tidak ditemukan. Silakan upload ulang.');
        }

        $errors = new ImportErrorBag();
        $stats = new ImportStats();
        $import = new MasterImport($errors, $stats, false);

        Excel::import($import, Storage::disk('local')->path($storedFile));

        Storage::disk('local')->delete($storedFile);

        $errorFile = $request->input('error_file');
        if ($errorFile && Storage::disk('local')->exists($errorFile)) {
            Storage::disk('local')->delete($errorFile);
        }

        $statsSummary = $stats->all();

        if ($errors->has()) {
            $fileName = 'import-errors/import-errors-' . now()->format('Ymd-His') . '.txt';
            Storage::disk('local')->put($fileName, $errors->toText());

            return redirect()
                ->back()
                ->with('warning', 'Import selesai dengan beberapa error.')
                ->with('import_errors_file', $fileName)
                ->with('import_stats', $statsSummary);
        }

        return redirect()->back()
            ->with('success', 'Import berhasil.')
            ->with('import_stats', $statsSummary);
    }

    public function downloadErrors(string $file)
    {
        $path = 'import-errors/' . $file;

        if (!Storage::disk('local')->exists($path)) {
            return redirect()->back()->with('error', 'File error tidak ditemukan.');
        }

        return Storage::disk('local')->download($path);
    }

    public function downloadTemplate()
    {
        return Excel::download(new TemplateExport(), 'template-import.xlsx');
    }
}
