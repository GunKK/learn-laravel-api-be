<?php

namespace App\Imports;

use App\Models\Subject;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\ImportFailed;

class SubjectsImport implements ShouldQueue, ToCollection, WithChunkReading, WithEvents, WithHeadingRow
{
    public $subjectImport;

    public function __construct($subjectImport)
    {
        $this->subjectImport = $subjectImport;
    }

    public function chunkSize(): int
    {
        return 200;
    }

    /**
     * @param  Collection  $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            DB::beginTransaction();
            try {
                $subject = new Subject();
                $subject->name = $row['name'];
                $subject->code = $row['code'];
                $subject->note = $row['note'];
                $subject->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json(['message' => 'Mã lỗi' . $e->getMessage()]);
            }
        }
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function (ImportFailed $event) {
                $this->subjectImport->update(['status' => 3]);
            },
        ];
    }
}
