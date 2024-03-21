<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;

class ImportStudentsCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $path;
    public $studentImport;
    /**
     * Create a new job instance.
     */
    public function __construct($path, $studentImport)
    {
        $this->path = $path;
        $this->studentImport = $studentImport;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->studentImport->update(['status' => 1]);

        Excel::import(new StudentsImport, $this->path);

        $this->studentImport->update(['status' => 2]);
    }

    public function fail($exception = null)
    {
        $this->studentImport->update(['status' => 3]);
    }
}
