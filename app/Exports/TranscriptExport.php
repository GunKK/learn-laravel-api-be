<?php

namespace App\Exports;

use App\Models\Report;
use App\Models\TeacherToSubject;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TranscriptExport implements FromQuery, WithHeadings, WithMapping
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $arr = TeacherToSubject::transcriptsFilter($this->request)->pluck('id')->toArray();

        return Report::with(['teacherToSubject', 'student'])->whereIn('teacher_to_subject_id', $arr);
    }

    public function headings(): array
    {
        return [
            'Student code',
            'Student',
            'Subject',
            'Teacher',
            'Score',
            'Status submit',
            'Semester',
            'Year',
        ];
    }

    public function map($row): array
    {
        return [
            $row->student->student_code,
            $row->student->full_name,
            $row->teacherToSubject->subject->name,
            $row->teacherToSubject->teacher->full_name,
            $row->mark,
            $row->path != '' ? 'submited' : 'not found',
            $row->teacherToSubject->semester,
            $row->teacherToSubject->year,
        ];
    }
}
