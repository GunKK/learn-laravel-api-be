<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TeachersImport;

class ImportTeachersCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $path;
    public $teacherImport;

    /**
     * Create a new job instance.
     */
    public function __construct($path, $teacherImport)
    {
        $this->path = $path;
        $this->teacherImport = $teacherImport;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->teacherImport->update(['status' => 1]);
        Excel::import(new TeachersImport, $this->path);
        $this->teacherImport->update(['status' => 2]);
    }

    public function fail($exception = null)
    {
        $this->teacherImport->update(['status' => 3]);
    }
}
