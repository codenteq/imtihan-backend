<?php

namespace App\Http\Controllers\API\Admin\User;

use App\Enums\Role;
use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Http\Resources\Admin\User\UserResource;
use App\Services\Admin\User\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends ApiController
{
    private UserService $userService;

    public function __construct(UserService $service)
    {
        $this->userService = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.user.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->userService->search($query));
        }

        return $this->successResponse($this->userService->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.user.create'),
            Response::HTTP_FORBIDDEN
        );

        $user = $this->userService->create($request);

        return $this->successResponse($user, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $user): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.user.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new UserResource($this->userService->show($user)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $user): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.user.update'),
            Response::HTTP_FORBIDDEN
        );

        $user = $this->userService->update($request, $user);

        return $this->successResponse($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $user): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.user.delete'),
            Response::HTTP_FORBIDDEN
        );

        $user = $this->userService->destroy($user);

        return $this->successResponse($user);
    }
}
