<?php

namespace App\Http\Controllers\API\Admin\Question;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Admin\Question\StoreQuestionCategoryRequest;
use App\Http\Requests\Admin\Question\UpdateQuestionCategoryRequest;
use App\Http\Resources\Admin\Question\QuestionCategoryResource;
use App\Services\Admin\Question\QuestionCategoryService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class QuestionCatergoryController extends ApiController
{
    private QuestionCategoryService $questionCategoryService;

    public function __construct(QuestionCategoryService $service)
    {
        $this->questionCategoryService = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.question.category.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');
        if ($query) {
            return $this->successResponse($this->questionCategoryService->search($query));
        }

        return $this->successResponse($this->questionCategoryService->paginate(['children']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestionCategoryRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.question.category.create'),
            Response::HTTP_FORBIDDEN
        );

        $category = $this->questionCategoryService->create($request);

        return $this->successResponse($category, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $category): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.question.category.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new QuestionCategoryResource($this->questionCategoryService->show($category, ['children'])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestionCategoryRequest $request, int $category): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.question.category.update'),
            Response::HTTP_FORBIDDEN
        );

        $category = $this->questionCategoryService->update($request, $category);

        return $this->successResponse($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $category): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.question.category.delete'),
            Response::HTTP_FORBIDDEN
        );

        $category = $this->questionCategoryService->destroy($category);

        return $this->successResponse($category);
    }
}
