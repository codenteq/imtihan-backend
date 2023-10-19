<?php

namespace App\Http\Controllers\API\Student\StaticPage;

use App\Http\Controllers\API\ApiController;
use App\Http\Resources\Student\StaticPage\StaticPageResource;
use App\Services\Student\StaticPage\StaticPageService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StaticPageController extends ApiController
{
    private StaticPageService $staticPageService;

    public function __construct(StaticPageService $service)
    {
        $this->staticPageService = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.static-page.list'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse($this->staticPageService->list());
    }

    /**
     * Display the specified resource.
     */
    public function show(int $staticPage): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.static-page.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new StaticPageResource($this->staticPageService->show($staticPage)));
    }
}
