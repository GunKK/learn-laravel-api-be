<?php

namespace App\Console\Commands;

use App\Exports\TranscriptExport;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class TestExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:report';

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
        $request = new Request();

        $export = new TranscriptExport($request);
        // dd($export->query()->first());
        dd($export->map(($export->query()->first())));
    }
}
