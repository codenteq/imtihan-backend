<?php

namespace App\Http\Controllers\API\Admin\Support;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Admin\Support\UpdateSupportRequest;
use App\Http\Resources\Admin\Support\SupportResource;
use App\Services\Admin\Support\SupportService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SupportController extends ApiController
{
    private SupportService $supportService;

    public function __construct(SupportService $service)
    {
        $this->supportService = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.support.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->supportService->search($query));
        }

        return $this->successResponse($this->supportService->paginate());
    }

    /**
     * Display the specified resource.
     */
    public function show(int $support): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.support.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new SupportResource($this->supportService->show($support)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupportRequest $request, int $support): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.support.update'),
            Response::HTTP_FORBIDDEN
        );

        $support = $this->supportService->update($request, $support);

        return $this->successResponse($support);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $support): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.support.delete'),
            Response::HTTP_FORBIDDEN
        );

        $support = $this->supportService->destroy($support);

        return $this->successResponse($support);
    }
}
