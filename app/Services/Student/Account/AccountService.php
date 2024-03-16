<?php

namespace App\Services\Student\Account;

use App\Models\User;
use App\Services\Base\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AccountService extends BaseService
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    /*
     * Update the specified resource in storage.
     *
     * @param object $request
     * @param int $id
     * @param array $where
     * @return object
     */
    public function update(object $request, int $id, array $where = []): object
    {
        $user = $this->model::findOrFail($id);

        if ($request->hasFile('avatar')) {
            if (! Str::startsWith($user->avatar, 'https://lh3.googleusercontent.com')) {
                Storage::delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars');
            $data = $request->safe()->merge(['avatar' => $path]);
        } else {
            $data = $request->safe();
        }

        $user->update($data->all());

        return $user;
    }

    public function passwordUpdate(object $request): object|bool
    {
        if (Hash::check($request->input('current_password'), Auth::user()->password)) {
            return $this->model::find(auth()->id())
                ->update(['password' => Hash::make($request->password)]);
        }

        return response()->json(['message' => 'Eski şifreniz eşleşmiyor'], 400);
    }
}
