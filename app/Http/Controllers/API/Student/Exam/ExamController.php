<?php

namespace App\Http\Controllers\API\Student\Exam;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Student\Exam\StoreExamRequest;
use App\Http\Resources\Student\Exam\ExamResource;
use App\Services\Student\Exam\ExamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExamController extends ApiController
{
    public function __construct(private readonly ExamService $examService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.exam.list'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(ExamResource::collection($this->examService->list([], ['user_id' => auth()->id()])));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExamRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.exam.create'),
            Response::HTTP_FORBIDDEN
        );

        $request->merge(['user_id' => auth()->id()]);
        $exam = $this->examService->create($request);

        return $this->successResponse($exam, Response::HTTP_CREATED);
    }

    /*
     * Store a user answer to exam
     */
    public function storeAnswer(Request $request, int $exam): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.exam.answer'),
            Response::HTTP_FORBIDDEN
        );

        $answer = $this->examService->storeUserAnswer($exam, $request);

        return $this->successResponse($answer, Response::HTTP_CREATED);
    }

    public function getExamResultAll(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.exam.results'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse($this->examService->getExamResultAll());
    }

    public function getExamResult(int $exam): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.exam.results'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse($this->examService->getExamResult($exam));
    }

    /**
     * Delete the exam
     */
    public function destroy(int $exam_id): JsonResponse
    {
        return $this->successResponse($this->examService->destroy($exam_id));
    }
}
