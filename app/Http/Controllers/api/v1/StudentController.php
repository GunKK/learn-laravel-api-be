<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\student\v1\CreateStudentRequest;
use App\Http\Requests\student\v1\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\Response;

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
}
