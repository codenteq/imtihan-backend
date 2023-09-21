<?php

namespace App\Http\Controllers\API\Admin\Condition;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Admin\Condition\StoreConditionRequest;
use App\Http\Requests\Admin\Condition\UpdateConditionRequest;
use App\Http\Resources\Admin\Condition\ConditionResource;
use App\Services\Admin\Condition\ConditionService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ConditionController extends ApiController
{
    private ConditionService $conditionService;

    public function __construct(ConditionService $service)
    {
        $this->conditionService = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.condition.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->conditionService->search($query));
        }

        return $this->successResponse($this->conditionService->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConditionRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.condition.create'),
            Response::HTTP_FORBIDDEN
        );

        $condition = $this->conditionService->create($request);

        return $this->successResponse($condition, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $condition): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.condition.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new ConditionResource($this->conditionService->show($condition)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConditionRequest $request, int $condition): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.condition.update'),
            Response::HTTP_FORBIDDEN
        );

        $condition = $this->conditionService->update($request, $condition);

        return $this->successResponse($condition);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $condition): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.condition.delete'),
            Response::HTTP_FORBIDDEN
        );

        $condition = $this->conditionService->destroy($condition);

        return $this->successResponse($condition);
    }
}
