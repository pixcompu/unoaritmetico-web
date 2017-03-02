<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultRequest;
use App\Http\Requests\CreateStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Repositories\Criterias\FromGroup;
use App\Repositories\Criterias\FromUser;
use App\Repositories\GroupRepository;
use App\Repositories\StudentRepository;
use App\Student;
use App\Transformers\StudentTransformer;


class StudentsController extends Controller
{
    private $studentRepository;
    private $groupRepository;

    function __construct(StudentRepository $studentRepository, GroupRepository $groupRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->groupRepository = $groupRepository;
    }

    /**
     * @SWG\Get(
     *     path="/students",
     *     summary="Obtains all students",
     *     tags={"Students"},
     *     description="Obtains all students",
     *     operationId="getStudents",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="page",
     *         in="query",
     *         type="integer",
     *         description="Page requested, default is 1",
     *         required=false
     *     ),
     *     @SWG\Parameter(
     *         name="per_page",
     *         in="query",
     *         type="integer",
     *         description="Items per page, default is 10",
     *         required=false
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Students created by user in the group",
     *          @SWG\Schema(ref="#/definitions/GetStudentsResponse")
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Request format isn't valid",
     *         @SWG\Schema(ref="#/definitions/Error"),
     *     ),
     *    @SWG\Response(
     *         response=401,
     *         description="Token is invalid",
     *         @SWG\Schema(ref="#/definitions/Error"),
     *     ),
     *     @SWG\Response(
     *         response=405,
     *         description="Invalid Method",
     *          @SWG\Schema(ref="#/definitions/Error"),
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Internal Error",
     *          @SWG\Schema(ref="#/definitions/Error"),
     *     ),
     * )
     */
    public function index(ConsultRequest $request)
    {
        $perPage = $request->has('per_page') ? $request->input('per_page') : 10;
        $this->studentRepository->pushCriteria(new FromUser($request->user()->id));
        $students = $this->studentRepository->paginate($perPage);
        return $this->responseTransformed($students, new StudentTransformer());
    }


    /**
     * @SWG\Post(
     *     path="/students",
     *     summary="Create a student",
     *     tags={"Students"},
     *     description="Create a student",
     *     operationId="storeStudent",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Student information",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/StoreStudentRequest")
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Account was registered",
     *         @SWG\Schema(ref="#/definitions/StoreStudentResponse")
     *     ),
     *     @SWG\Response(
     *         response=405,
     *         description="Invalid Method",
     *          @SWG\Schema(ref="#/definitions/Error"),
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Invalid Fields",
     *          @SWG\Schema(ref="#/definitions/Validation"),
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Internal Error",
     *          @SWG\Schema(ref="#/definitions/Error"),
     *     ),
     * )
     */
    public function store(CreateStudentRequest $request)
    {
        $student = new Student($request->only(['name', 'age']));
        $this->groupRepository->find($request->input('group_id'))->students()->save($student);
        return $this->responseTransformed($student, new StudentTransformer());
    }

    /**
     * @SWG\Get(
     *     path="/students/{studentId}",
     *     summary="Display a single student",
     *     tags={"Students"},
     *     description="Display a single student",
     *     operationId="getGroups",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="studentId",
     *         in="path",
     *         description="Id of student to retrieve",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Groups created by user",
     *          @SWG\Schema(ref="#/definitions/DetailStudentResponse")
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Request format isn't valid",
     *         @SWG\Schema(ref="#/definitions/Error"),
     *     ),
     *    @SWG\Response(
     *         response=401,
     *         description="Token is invalid",
     *         @SWG\Schema(ref="#/definitions/Error"),
     *     ),
     *     @SWG\Response(
     *         response=405,
     *         description="Invalid Method",
     *          @SWG\Schema(ref="#/definitions/Error"),
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Internal Error",
     *          @SWG\Schema(ref="#/definitions/Error"),
     *     ),
     * )
     */
    public function show($studentId)
    {
        return $this->responseTransformed($this->studentRepository->find($studentId), new StudentTransformer());
    }


    /**
     * @SWG\Put(
     *     path="/students/{studentId}",
     *     summary="Update a student",
     *     tags={"Students"},
     *     description="Update a student",
     *     operationId="updateStudent",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Group to update",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/UpdateStudentRequest")
     *     ),
     *     @SWG\Parameter(
     *         name="studentId",
     *         in="path",
     *         description="Id of student to update",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Group was updated",
     *         @SWG\Schema(ref="#/definitions/UpdateStudentResponse")
     *     ),
     *     @SWG\Response(
     *         response=405,
     *         description="Invalid Method",
     *          @SWG\Schema(ref="#/definitions/Error"),
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Invalid Fields",
     *          @SWG\Schema(ref="#/definitions/Validation"),
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Internal Error",
     *          @SWG\Schema(ref="#/definitions/Error"),
     *     ),
     * )
     */
    public function update(UpdateStudentRequest $request, $studentId)
    {
        $student = $this->studentRepository->find($studentId);
        $student->fill($request->only(['name', 'age']));
        $student->save();
        return $this->responseTransformed($student, new StudentTransformer());
    }

    /**
     * @SWG\Delete(
     *     path="/students/{studentId}",
     *     summary="Delete a student",
     *     tags={"Students"},
     *     description="Delete a student",
     *     operationId="deleteStudent",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="studentId",
     *         in="path",
     *         description="Id of student to delete",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="Group was deleted"
     *     ),
     *     @SWG\Response(
     *         response=405,
     *         description="Invalid Method",
     *          @SWG\Schema(ref="#/definitions/Error"),
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Invalid Fields",
     *          @SWG\Schema(ref="#/definitions/Validation"),
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Internal Error",
     *          @SWG\Schema(ref="#/definitions/Error"),
     *     ),
     * )
     */
    public function destroy($studentId)
    {
        $this->studentRepository->delete($studentId);
        return $this->response->noContent();
    }
}
