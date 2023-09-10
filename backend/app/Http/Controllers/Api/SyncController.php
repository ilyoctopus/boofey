<?php

namespace App\Http\Controllers\Api;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Http\Requests\AcademicYears\StoreAcademicYearRequest;
use App\Http\Requests\AcademicYears\UpdateAcademicYearRequest;
use App\Http\Requests\Students\SyncStudentFaceRequest;
use App\Http\Requests\Students\SyncStudentNfcRequest;
use App\Models\File;
use App\Models\School;
use App\Models\Student;
use Carbon\Carbon;

class SyncController extends Controller
{
    /**
     * Display all academicYears
     * 
     * @return \Illuminate\Http\Response
     */
    public function sync(Request $request) 
    {
        $canteen = $request->get('canteen');
        $students = $canteen->school->students;

        $studentsData = [];

        foreach($students as $student){
            $studentsData[] = [
                'id' => $student->id,
                'firstname' => $student->firstname,
                'lastname' => $student->lastname,
                'class' => $student->class,
                'nfc_id' => $student->nfc_id,
                'face_id' => $student->face_id,
                'onhold' => $student->onhold,
                'subscriped' => $student->subscriped,
                'image' => [
                    'id' => $student->image->id,
                    'full_path' => $student->image->full_path,
                ],
                'tookSnackToday' => $student->tookSnackToday,
                'tookMainMealToday' => $student->tookMainMealToday,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'school' => [
                    'id' => $canteen->school->id,
                    'name' => $canteen->school->name,
                    'logo' => ($student->school->logo != null) ? [
                        'id' => $student->school->logo->id,
                        'full_path' => $student->school->logo->full_path,
                    ] : null,
                ],
                'canteen' => [
                    'id' => $canteen->id,
                    'name' => $canteen->name,
                    'address' => $canteen->address
                ],
                'current_queue' => ($canteen->currentQueue != null) ? [
                    'id' => $canteen->currentQueue->id,
                    'started_at' => $canteen->currentQueue->started_at,
                    'closed_at' => $canteen->currentQueue->closed_at,
                ] : null,
                'students' => $studentsData,
                'synced_at' => Carbon::now()
            ]
        ]);
    }

    public function nfc($id, SyncStudentNfcRequest $request) 
    {
        $student = Student::find($id);
        
        if (!$student) {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    '404' => 'Not found.'
                ]
            ], 404);
        }

        $student->update([
            'nfc_id' => $request->get('nfc_id')
        ]);

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function face($id, SyncStudentFaceRequest $request) 
    {
        $student = Student::find($id);
        
        if (!$student) {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    '404' => 'Not found.'
                ]
            ], 404);
        }

        $student->update([
            'face_id' => $request->get('face_id')
        ]);

        return response()->json([
            'status' => 'success'
        ]);
    }


}