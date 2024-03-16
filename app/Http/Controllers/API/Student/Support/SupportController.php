<?php

namespace App\Http\Controllers\API\Student\Support;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Student\Support\StoreSupportRequest;
use App\Services\Student\Support\SupportService;
use Illuminate\Auth\Access\AuthorizationException;
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
        abort_unless(auth()->user()->tokenCan('student.support.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->supportService->search(query: $query, where: [
                'user_id' => auth()->id()
            ]));
        }

        return $this->successResponse($this->supportService->paginate(where: [
            'user_id' => auth()->id(),
            'is_active' => false
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupportRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.support.create'),
            Response::HTTP_FORBIDDEN
        );

        $support = $this->supportService->create($request);

        return $this->successResponse($support, Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws AuthorizationException
     */
    public function destroy(int $support): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.support.delete'),
            Response::HTTP_FORBIDDEN
        );

        $this->authorize('delete', $this->supportService->show($support));

        $support = $this->supportService->destroy($support, ['user_id' => auth()->id()]);

        return $this->successResponse($support);
    }
}
