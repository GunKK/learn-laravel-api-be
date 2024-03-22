<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\student\v1\CreateStudentRequest;
use App\Http\Requests\student\v1\ReportRequest;
use App\Http\Requests\student\v1\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\TeacherToSubject;

class StudentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateStudentRequest $request)
    {
        $student = new Student($request->validated());
        $student->save();

        return response()->json($student, Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, string $id)
    {
        $student = Student::findOrFail($id);
        $student->update($request->validated());

        return response()->json($student, Response::HTTP_OK);
    }

    public function storeReport(ReportRequest $request)
    {
        $teacherToSubject = TeacherToSubject::find($request->teacher_to_subject_id);
        $year = $teacherToSubject->year;
        $semester = $teacherToSubject->semester;
        $file_name = date('Ymd_His_').$request->file->getClientOriginalName();
        $file_path = storage_path('app\\data\\reports\\'.$year.'\\'.$semester.'\\'.$teacherToSubject->id.'\\'.$file_name);

        $report = new Report();
        $report->student_id = Auth::user()->student_id;
        $report->teacher_to_subject_id = $request->teacher_to_subject_id;
        $report->title = $request->title;
        $report->path = $file_path;
        $report->save();

        $request->file->move(storage_path('app\\data\\reports\\'.$year.'\\'.$semester.'\\'.$teacherToSubject->id), $file_name);

        return response()->json([
            'message' => 'upload report successfully'
        ], Response::HTTP_CREATED);
    }
}
