<?php

namespace App\Http\Controllers\API\Admin\Announcement;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Admin\Announcement\StoreAnnouncementRequest;
use App\Http\Requests\Admin\Announcement\UpdateAnnouncementRequest;
use App\Http\Resources\Admin\Announcement\AnnouncementResource;
use App\Services\Admin\Announcement\AnnouncementService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AnnouncementController extends ApiController
{
    private AnnouncementService $announcementService;

    public function __construct(AnnouncementService $service)
    {
        $this->announcementService = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.announcement.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->announcementService->search($query));
        }

        return $this->successResponse($this->announcementService->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnnouncementRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.announcement.create'),
            Response::HTTP_FORBIDDEN
        );

        $announcement = $this->announcementService->create($request);

        return $this->successResponse($announcement, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $announcement): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.announcement.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new AnnouncementResource($this->announcementService->show($announcement)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnnouncementRequest $request, int $announcement): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.announcement.update'),
            Response::HTTP_FORBIDDEN
        );

        $announcement = $this->announcementService->update($request, $announcement);

        return $this->successResponse($announcement);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $announcement): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.announcement.delete'),
            Response::HTTP_FORBIDDEN
        );

        $announcement = $this->announcementService->destroy($announcement);

        return $this->successResponse($announcement);
    }
}
