<?php

namespace App\Http\Controllers\API\Student\ClassSchedule;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Student\ClassSchedule\StoreClassScheduleRequest;
use App\Http\Requests\Student\ClassSchedule\UpdateClassScheduleRequest;
use App\Http\Resources\Student\ClassSchedule\ClassScheduleResource;
use App\Services\Student\ClassSchedule\ClassScheduleService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ClassScheduleController extends ApiController
{
    private ClassScheduleService $classScheduleService;

    public function __construct(ClassScheduleService $service)
    {
        $this->classScheduleService = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.class-schedule.list'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(ClassScheduleResource::collection($this->classScheduleService->list([], ['user_id' => auth()->id()])));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassScheduleRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.class-schedule.create'),
            Response::HTTP_FORBIDDEN
        );

        $classSchedule = $this->classScheduleService->create($request);

        return $this->successResponse($classSchedule, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $class_schedule): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.class-schedule.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new ClassScheduleResource($this->classScheduleService->show($class_schedule, [], ['user_id' => auth()->id()])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassScheduleRequest $request, int $class_schedule): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.class-schedule.update'),
            Response::HTTP_FORBIDDEN
        );

        $class_schedule = $this->classScheduleService->update($request, $class_schedule, ['user_id' => auth()->id()]);

        return $this->successResponse($class_schedule);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws AuthorizationException
     */
    public function destroy(int $class_schedule): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.class-schedule.delete'),
            Response::HTTP_FORBIDDEN
        );

        $this->authorize('delete', $this->classScheduleService->show($class_schedule));

        $class_schedule = $this->classScheduleService->destroy($class_schedule, ['user_id' => auth()->id()]);

        return $this->successResponse($class_schedule);
    }
}
