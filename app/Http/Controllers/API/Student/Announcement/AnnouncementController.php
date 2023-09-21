<?php

namespace App\Http\Controllers\API\Student\Announcement;

use App\Http\Controllers\API\ApiController;
use App\Http\Resources\Student\Announcement\AnnouncementResource;
use App\Services\Student\Announcement\AnnouncementService;
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
        abort_unless(auth()->user()->tokenCan('student.announcement.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->announcementService->search($query));
        }

        return $this->successResponse($this->announcementService->paginate());
    }

    /**
     * Display the specified resource.
     */
    public function show(int $announcement): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.announcement.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new AnnouncementResource($this->announcementService->show($announcement)));
    }
}
