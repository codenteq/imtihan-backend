<?php

namespace App\Http\Controllers\API\Admin\Lesson;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Admin\Lesson\StoreLessonRequest;
use App\Http\Requests\Admin\Lesson\UpdateLessonRequest;
use App\Http\Resources\Admin\Lesson\LessonResource;
use App\Services\Admin\Lesson\LessonService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LessonController extends ApiController
{
    private LessonService $lessonService;

    public function __construct(LessonService $service)
    {
        $this->lessonService = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.lesson.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->lessonService->search($query));
        }

        return $this->successResponse($this->lessonService->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLessonRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.lesson.create'),
            Response::HTTP_FORBIDDEN
        );

        $lesson = $this->lessonService->create($request);

        return $this->successResponse($lesson, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $lesson): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.lesson.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new LessonResource($this->lessonService->show($lesson)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLessonRequest $request, int $lesson): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.lesson.update'),
            Response::HTTP_FORBIDDEN
        );

        $lesson = $this->lessonService->update($request, $lesson);

        return $this->successResponse($lesson);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $lesson): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.lesson.delete'),
            Response::HTTP_FORBIDDEN
        );

        $lesson = $this->lessonService->destroy($lesson);

        return $this->successResponse($lesson);
    }
}
