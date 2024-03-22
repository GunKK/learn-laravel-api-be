<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\report\v1\SetMarkReportRequest;
use App\Http\Resources\SubjectResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\teacherToSubject\v1\TeacherToSubjectRequest;
use App\Models\Subject;
use App\Models\TeacherToSubject;
use App\Models\Report;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class TeacherController extends Controller
{
    public function createTeacherToSubject(TeacherToSubjectRequest $request)
    {
        $teacherToSubject = new TeacherToSubject($request->validated());
        $teacherToSubject->teacher_id = Auth::user()->id;
        $teacherToSubject->save();

        return response()->json($teacherToSubject);
    }

    public function getSubjects()
    {
        return SubjectResource::collection(Subject::all());
    }

    public function setMarkReport(SetMarkReportRequest $request, string $id)
    {
        $this->authorize('teacher_set_mark');
        $report = Report::findOrFail($id);
        $report->mark = $request->mark;
        $report->save();

        return response()->json([
            'message' => 'update mark successfully'
        ], Response::HTTP_OK);
    }
}
