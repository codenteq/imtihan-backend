<?php

namespace App\Http\Controllers\API\Student\ExamType;

use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Student\ExamType\ExamTypeResource;
use App\Models\ExamType;
use App\Services\Student\ExamType\ExamTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamTypeController extends ApiController
{
    public function __construct(private readonly ExamTypeService $examTypeService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        return $this->successResponse(ExamTypeResource::collection($this->examTypeService->list()));
    }
}
