<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\file\ExcelRequest;
use App\Jobs\ImportTeachersCsv;
use App\Jobs\ImportStudentsCsv;
use App\Jobs\ImportSubjectsCsv;
use App\Models\Import;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ImportController extends Controller
{
    public function importStudents(ExcelRequest $request)
    {
        $file_name = date('Ymd_His_').$request->csv_import->getClientOriginalName();
        $file_path = storage_path('app\\data\\students\\'.$file_name);
        $import = new Import();
        $import->name = $file_name;
        $import->path = $file_path;
        $import->status = 0;
        $import->created_by = Auth::user()->name;
        $import->save();

        $request->csv_import->move(storage_path('app\\data\\students\\'), $file_name);
        $studentImport = Import::where('name', $file_name)->first();
        $studentImport = $import;
        $path = $file_path;
        ImportStudentsCsv::dispatch($path, $studentImport)->delay(10);

        return response()->json([
            'message' => 'Tải file thành công, đang chờ xử lý'
        ], Response::HTTP_OK);
    }

    public function importTeachers(ExcelRequest $request)
    {
        $file_name = date('Ymd_His_').$request->csv_import->getClientOriginalName();
        $file_path = storage_path('app\\data\\teachers\\'.$file_name);
        $import = new Import();
        $import->name = $file_name;
        $import->path = $file_path;
        $import->status = 0;
        $import->created_by = Auth::user()->name;
        $import->save();

        $request->csv_import->move(storage_path('app\\data\\teachers\\'), $file_name);
        $teacherImport = Import::where('name', $file_name)->first();
        $teacherImport = $import;
        $path = $file_path;
        ImportTeachersCsv::dispatch($path, $teacherImport)->delay(10);

        return response()->json([
            'message' => 'Tải file thành công, đang chờ xử lý'
        ], Response::HTTP_OK);
    }

    public function importSubjects(ExcelRequest $request)
    {
        $file_name = date('Ymd_His_').$request->csv_import->getClientOriginalName();
        $file_path = storage_path('app\\data\\subjects\\'.$file_name);
        $import = new Import();
        $import->name = $file_name;
        $import->path = $file_path;
        $import->status = 0;
        $import->created_by = Auth::user()->name;
        $import->save();

        $request->csv_import->move(storage_path('app\\data\\\subjects\\'), $file_name);
        $subjectImport = Import::where('name', $file_name)->first();
        $subjectImport = $import;
        $path = $file_path;
        ImportSubjectsCsv::dispatch($path, $subjectImport)->delay(10);

        return response()->json([
            'message' => 'Tải file thành công, đang chờ xử lý'
        ], Response::HTTP_OK);
    }
}
