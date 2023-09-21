<?php

namespace App\Http\Controllers\API\Student\Account;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Student\Account\UpdateAccountRequest;
use App\Http\Resources\Student\Account\AccountResource;
use App\Services\Student\Account\AccountService;
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
        abort_unless(auth()->user()->tokenCan('student.account.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->accountService->search($query, 10, ['id' => auth()->id()]));
        }

        return $this->successResponse($this->accountService->paginate([], ['id' => auth()->id()]));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $account): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.account.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new AccountResource($this->accountService->show($account, [], ['id' => auth()->id()])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, int $account): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.account.update'),
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
        abort_unless(auth()->user()->tokenCan('student.account.delete'),
            Response::HTTP_FORBIDDEN
        );

        $account = $this->accountService->destroy($account);

        return $this->successResponse($account);
    }
}
