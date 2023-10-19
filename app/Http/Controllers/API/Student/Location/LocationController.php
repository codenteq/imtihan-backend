<?php

namespace App\Http\Controllers\API\Student\Location;

use App\Http\Controllers\API\ApiController;
use App\Services\Student\City\CityService;
use App\Services\Student\Country\CountryService;
use App\Services\Student\State\StateService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LocationController extends ApiController
{
    private CountryService $countryService;

    private CityService $cityService;

    private StateService $stateService;

    public function __construct(CountryService $countryService, CityService $cityService, StateService $stateService)
    {
        $this->countryService = $countryService;
        $this->cityService = $cityService;
        $this->stateService = $stateService;
    }

    /**
     * Display a listing of the country resource.
     */
    public function getCountry(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.country.list'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse($this->countryService->list());
    }

    /**
     * Display a listing of the city resource.
     */
    public function getCity(int $countryId): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.city.list'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse($this->cityService->list([], ['country_id' => $countryId]));
    }

    /**
     * Display a listing of the state resource.
     */
    public function getState(int $cityId): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.state.list'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse($this->stateService->list([], ['city_id' => $cityId]));
    }
}
