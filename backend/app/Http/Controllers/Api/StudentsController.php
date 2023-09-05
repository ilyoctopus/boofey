<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Requests\Students\StoreStudentRequest;
use App\Http\Requests\Students\UpdateStudentRequest;
use App\Models\AcademicYear;
use App\Models\Father;
use App\Models\File;
use App\Models\School;
use Illuminate\Database\Eloquent\Scope;

class StudentsController extends Controller
{
    /**
     * Display all students
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {
        $perPage = limitPerPage($request->query('perPage', 10));
        $page = checkPageIfNull($request->query('page', 1));
        $search = $request->query('search');

        $students = Student::latest()->with([
            'image:id,path,current_name', 
            'school:id,name',
            'academicYear:id,name,from,to'
        ]);

        if ($search) {
            $students->where('firstname', 'like', '%' . $search . '%')
            ->orWhere('lastname', 'like', '%' . $search . '%');
        }

        $students = $students->paginate($perPage, ['*'], 'page', $page);
        $fathers = Father::get();
        $schools = School::get();
        $academicYears = AcademicYear::get();

        $response = [
            'status' => 'success',
            'data' => [
                'students' => $students->items(), 
                'fathers' => $fathers, 
                'schools' => $schools, 
                'academicYears' => $academicYears, 
            ],
            'pagination' => [
                'per_page' => $students->perPage(),
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'from' => $students->firstItem(),
                'to' => $students->lastItem(),
                'total' => $students->total(),
                'pages' => pages($students->currentPage(), $students->lastPage())
            ],
        ];

        return response()->json($response);
    }

    /**
     * Store a newly created student
     * 
     * @param Student $student
     * @param StoreStudentRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request) 
    {
        $file = uploadFile($request->file('file'), 'students');

        $student = Student::create(array_merge(
            $request->validated(),
            ['file_id' => $file->id]
        ));

        $student->save();

        return response()->json([
            'status' => 'success'
        ]);
    }

    /**
     * Show student data
     * 
     * @param Student $student
     * 
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        $student = Student::with([
            'image:id,path,current_name', 
            'school:id,name',
            'academicYear:id,name,from,to'
        ])->find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    '404' => 'Not found.'
                ]
            ], 404);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'student' => $student
            ]
        ]);
    }

    /**
     * Update student data
     * 
     * @param Student $student
     * @param UpdateStudentRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateStudentRequest $request) 
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

        $file = File::find($student->file_id);

        if($request->file('file')) {
            removeFile($file);

            $file = uploadFile($request->file('file'), 'students');
        }

        $student->update(array_merge(
            $request->validated(),
            ['file_id' => $file->id]
        ));

        return response()->json([
            'status' => 'success'
        ]);
    }

    /**
     * Delete student data
     * 
     * @param Student $student
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) 
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

        $file = File::find($student->file_id);

        $student->delete();

        removeFile($file);

        return response()->json([
            'status' => 'success'
        ]);
    }
}