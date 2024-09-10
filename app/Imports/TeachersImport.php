<?php

namespace App\Imports;

use App\Models\Teacher;
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

class TeachersImport implements ShouldQueue, ToCollection, WithChunkReading, WithEvents, WithHeadingRow
{
    // public function startRow(): int
    // {
    //     return 2;
    // }

    public $teacherImport;

    public function __construct($teacherImport)
    {
        $this->teacherImport = $teacherImport;
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
                $teacher = new Teacher();
                $teacher->last_name = $row['last_name'];
                $teacher->first_name = $row['first_name'];
                $teacher->teacher_code = $row['teacher_code'];
                $teacher->department = $row['department'];
                $teacher->faculty = $row['faculty'];
                $teacher->address = $row['address'];
                $teacher->phone = $row['phone'];
                $teacher->note = $row['note'];
                $teacher->save();

                $user = new User();
                $user->name = $row['last_name'] . ' ' . $row['first_name'];
                $user->email = $row['email'];
                $user->password = Hash::make('Bmvt@hcmut');
                $user->role_id = 3;
                $user->teacher_id = $teacher->id;
                $user->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json(['message' => 'Mã lỗi' . $e->getMessage()]);
            }
        }
    }

    // handle error
    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function (ImportFailed $event) {
                $this->teacherImport->update(['status' => 3]);
            },
        ];
    }
}
