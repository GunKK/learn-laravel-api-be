<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    public function viewReport(string $id)
    {
        $this->authorize('view_download_report');
        $report = Report::findOrFail($id);
        $pathReport = $report->path;
        return response()->file($pathReport);
    }

    public function downloadReport(string $id)
    {
        $report = Report::findOrFail($id);
        $pathReport = $report->path;
        return response()->download($pathReport);
    }
}
