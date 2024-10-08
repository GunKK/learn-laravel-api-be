<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\ImportFailed;

class StudentsImport implements ShouldQueue, ToCollection, WithChunkReading, WithEvents, WithHeadingRow
{
    // public function startRow(): int
    // {
    //     return 2;
    // }

    // In case your heading row is not on the first row, you can easily specify this in your import class:
    // public function headingRow(): int
    // {
    //     return 2;
    // }

    public $studentImport;

    public function __construct($studentImport)
    {
        $this->studentImport = $studentImport;
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
                $student = new Student();
                $student->last_name = $row['last_name'];
                $student->first_name = $row['first_name'];
                $student->student_code = $row['student_code'];
                $student->department = $row['department'];
                $student->faculty = $row['faculty'];
                $student->address = $row['address'];
                $student->phone = $row['phone'];
                $student->note = $row['note'];
                $student->save();

                $user = new User();
                $user->name = $row['last_name'] . ' ' . $row['first_name'];
                $user->email = $row['email'];
                $user->password = Hash::make('default1234567');
                $user->role_id = 4;
                $user->student_id = $student->id;
                $user->save();

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
                $this->studentImport->update(['status' => 3]);
            },
        ];
    }
}
