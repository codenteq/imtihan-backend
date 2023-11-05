<?php

namespace App\Http\Controllers\API\Student\Account;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Student\Account\UpdateAccountRequest;
use App\Http\Resources\Student\Account\AccountResource;
use App\Services\Student\Account\AccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends ApiController
{
    private AccountService $accountService;

    public function __construct(AccountService $service)
    {
        $this->accountService = $service;
    }

    /**
     * Display the specified resource.
     */
    public function show(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.account.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new AccountResource($this->accountService->show(auth()->id())));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.account.update'),
            Response::HTTP_FORBIDDEN
        );

        $account = $this->accountService->update($request, auth()->id());

        return $this->successResponse($account);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.account.delete'),
            Response::HTTP_FORBIDDEN
        );

        $account = $this->accountService->destroy(auth()->id());

        return $this->successResponse($account);
    }

    /**
     * Update the specified resource in storage.
     */
    public function passwordUpdate(Request $request): JsonResponse | bool
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'current_password' => ['required']
        ]);

        return $this->accountService->passwordUpdate($request);
    }
}
