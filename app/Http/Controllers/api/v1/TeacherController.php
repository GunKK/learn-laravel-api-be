<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubjectResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\teacherToSubject\v1\TeacherToSubjectRequest;
use App\Models\Subject;
use App\Models\TeacherToSubject;

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
}
