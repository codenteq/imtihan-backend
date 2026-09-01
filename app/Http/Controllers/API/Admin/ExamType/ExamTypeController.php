<?php

namespace App\Http\Controllers\API\Admin\ExamType;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Admin\ExamType\StoreExamTypeRequest;
use App\Http\Requests\Admin\ExamType\UpdateExamTypeRequest;
use App\Http\Resources\Admin\ExamType\ExamTypeResource;
use App\Services\Admin\ExamType\ExamTypeService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ExamTypeController extends ApiController
{
    public function __construct(private readonly ExamTypeService $examTypeService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_unless(auth()->user()->tokenCan('admin.exam-type.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->examTypeService->search($query));
        }

        return $this->successResponse($this->examTypeService->paginate());
    }

    public function getCategories(int $exam_type): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.exam-type.list'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse($this->examTypeService->getCategories($exam_type));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExamTypeRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.exam-type.create'),
            Response::HTTP_FORBIDDEN
        );

        $examType = $this->examTypeService->create($request);

        return $this->successResponse($examType, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $exam_type): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.exam-type.show'),
            Response::HTTP_FORBIDDEN
        );

        $examType = new ExamTypeResource($this->examTypeService->show($exam_type));

        return $this->successResponse($examType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExamTypeRequest $request, int $exam_type): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.exam-type.update'),
            Response::HTTP_FORBIDDEN
        );

        $examType = $this->examTypeService->update($request, $exam_type);

        return $this->successResponse($examType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $exam_type): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.exam-type.delete'),
            Response::HTTP_FORBIDDEN
        );

        $examType = $this->examTypeService->destroy($exam_type);

        return $this->successResponse($examType);
    }
}
