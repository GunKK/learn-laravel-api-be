<?php

namespace App\Console\Commands;

use App\Exports\TranscriptExport;
use App\Models\Export;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportTranscripts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:transcripts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $request = new Request(['year' => Carbon::now()->year]);

        $fileName = date('Ymd_His_') . 'transcripts.csv';
        $filePath = 'app/data/transcrips/' . Carbon::now()->year . '/' . $fileName;
        Excel::store(
            new TranscriptExport($request),
            'data/transcrips/' . Carbon::now()->year . '/' . $fileName,
            'local',
            \Maatwebsite\Excel\Excel::CSV
        );
        $export = new Export([
            'name' => $fileName,
            'path' => $filePath,
        ]);
        $export->save();

        return 0;
    }
}
