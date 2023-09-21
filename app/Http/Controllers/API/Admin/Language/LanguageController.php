<?php

namespace App\Http\Controllers\API\Admin\Language;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Admin\Language\StoreLanguageRequest;
use App\Http\Requests\Admin\Language\UpdateLanguageRequest;
use App\Http\Resources\Admin\Language\LanguageResource;
use App\Services\Admin\Language\LanguageService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LanguageController extends ApiController
{
    private LanguageService $languageService;

    public function __construct(LanguageService $service)
    {
        $this->languageService = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.language.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->languageService->search($query));
        }

        return $this->successResponse($this->languageService->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLanguageRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.language.create'),
            Response::HTTP_FORBIDDEN
        );

        $language = $this->languageService->create($request);

        return $this->successResponse($language, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $language): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.language.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new LanguageResource($this->languageService->show($language)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLanguageRequest $request, int $language): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.language.update'),
            Response::HTTP_FORBIDDEN
        );

        $language = $this->languageService->update($request, $language);

        return $this->successResponse($language);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $language): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.language.delete'),
            Response::HTTP_FORBIDDEN
        );

        $language = $this->languageService->destroy($language);

        return $this->successResponse($language);
    }
}
