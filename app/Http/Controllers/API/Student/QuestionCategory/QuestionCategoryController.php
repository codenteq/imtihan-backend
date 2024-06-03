<?php

namespace App\Http\Controllers\API\Student\QuestionCategory;

use App\Http\Controllers\API\ApiController;
use App\Http\Resources\Student\QuestionCategory\QuestionCategoryResource;
use App\Services\Student\QuestionCategory\QuestionCategoryService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class QuestionCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(QuestionCategoryService $questionCategoryService): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.question-category.list'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(
            QuestionCategoryResource::collection($questionCategoryService->list(with: ['childrenTree']))
        );
    }
}
