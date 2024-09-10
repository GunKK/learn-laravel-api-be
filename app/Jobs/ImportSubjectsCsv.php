<?php

namespace App\Jobs;

use App\Imports\SubjectsImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ImportSubjectsCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $path;

    public $subjectImport;

    /**
     * Create a new job instance.
     */
    public function __construct($path, $subjectImport)
    {
        $this->path = $path;
        $this->subjectImport = $subjectImport;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->subjectImport->update(['status' => 1]);

        Excel::import(new SubjectsImport($this->subjectImport), $this->path);

        $this->subjectImport->update(['status' => 2]);
    }

    public function fail($exception = null)
    {
        $this->subjectImport->update(['status' => 3]);
    }
}
