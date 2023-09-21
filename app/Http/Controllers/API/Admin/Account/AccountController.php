<?php

namespace App\Http\Controllers\API\Admin\Account;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Admin\Account\StoreAccountRequest;
use App\Http\Requests\Admin\Account\UpdateAccountRequest;
use App\Http\Resources\Admin\Account\AccountResource;
use App\Models\User;
use App\Services\Admin\Account\AccountService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends ApiController
{
    private AccountService $accountService;

    public function __construct(AccountService $service)
    {
        $this->accountService = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.account.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->accountService->search($query));
        }

        return $this->successResponse($this->accountService->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.account.create'),
            Response::HTTP_FORBIDDEN
        );

        $request->merge([
            'role' => User::Admin,
        ]);

        $account = $this->accountService->create($request);

        return $this->successResponse($account, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $account): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.account.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new AccountResource($this->accountService->show($account)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, int $account): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.account.update'),
            Response::HTTP_FORBIDDEN
        );

        $account = $this->accountService->update($request, $account);

        return $this->successResponse($account);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $account): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('admin.account.delete'),
            Response::HTTP_FORBIDDEN
        );

        $account = $this->accountService->destroy($account);

        return $this->successResponse($account);
    }
}
