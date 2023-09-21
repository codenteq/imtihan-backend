<?php

namespace App\Http\Controllers\API\Admin\Question;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Admin\Question\StoreQuestionRequest;
use App\Http\Requests\Admin\Question\UpdateQuestionRequest;
use App\Http\Resources\Admin\Question\QuestionResource;
use App\Services\Admin\Question\QuestionService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends ApiController
{
    private QuestionService $questionService;

    public function __construct(QuestionService $service)
    {
        $this->questionService = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.question.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->questionService->search($query));
        }

        $list = request()->query('list');

        if ($list === 'all') {
            return $this->successResponse($this->questionService->list(['category', 'language', 'options']));
        }

        return $this->successResponse($this->questionService->paginate(['category', 'language', 'options']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestionRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.question.create'),
            Response::HTTP_FORBIDDEN
        );

        $question = $this->questionService->create($request);

        return $this->successResponse($question, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $question): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.question.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new QuestionResource($this->questionService->show($question, ['category', 'language', 'options'])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestionRequest $request, int $question): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.question.update'),
            Response::HTTP_FORBIDDEN
        );

        $question = $this->questionService->update($request, $question);

        return $this->successResponse($question);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $question): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.question.delete'),
            Response::HTTP_FORBIDDEN
        );

        $question = $this->questionService->destroy($question);

        return $this->successResponse($question);
    }
}
