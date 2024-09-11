<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    public function viewReport(string $id)
    {
        $report = Report::findOrFail($id);
        // if (Gate::denies('view_download_report', $report)) {
        //     return response()->json(['message' => 'Forbidden', 'status' => 403], Response::HTTP_FORBIDDEN);
        // }
        $pathReport = $report->path;

        return response()->file($pathReport);
    }

    public function downloadReport(string $id)
    {
        $report = Report::findOrFail($id);
        // if (Gate::denies('view_download_report', $report)) {
        //     return response()->json(['message' => 'Forbidden', 'status' => 403], Response::HTTP_FORBIDDEN);
        // }
        $pathReport = $report->path;

        return response()->download($pathReport);
    }
}
