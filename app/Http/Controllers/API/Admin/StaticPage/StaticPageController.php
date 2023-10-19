<?php

namespace App\Http\Controllers\API\Admin\StaticPage;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Admin\StaticPage\StoreStaticPageRequest;
use App\Http\Requests\Admin\StaticPage\UpdateStaticPageRequest;
use App\Http\Resources\Admin\StaticPage\StaticPageResource;
use App\Services\Admin\StaticPage\StaticPageService;
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
        abort_unless(auth()->user()->tokenCan('admin.static-page.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->staticPageService->search($query));
        }

        return $this->successResponse($this->staticPageService->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStaticPageRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.static-page.create'),
            Response::HTTP_FORBIDDEN
        );

        $staticPage = $this->staticPageService->create($request);

        return $this->successResponse($staticPage, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $staticPage): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.static-page.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new StaticPageResource($this->staticPageService->show($staticPage)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaticPageRequest $request, int $staticPage): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.static-page.update'),
            Response::HTTP_FORBIDDEN
        );

        $staticPage = $this->staticPageService->update($request, $staticPage);

        return $this->successResponse($staticPage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $staticPage): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.static-page.delete'),
            Response::HTTP_FORBIDDEN
        );

        $staticPage = $this->staticPageService->destroy($staticPage);

        return $this->successResponse($staticPage);
    }
}
