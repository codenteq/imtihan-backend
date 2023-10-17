<?php

namespace App\Services\Student\Account;

use App\Models\User;
use App\Services\Base\BaseService;
use Illuminate\Support\Facades\Storage;

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
        $avatar = $this->model::findOrFail($id);

        if ($request->hasFile('avatar')) {
            if (Storage::exists($avatar->avatar)) {
                Storage::delete($avatar->avatar);
            }
            $path = $request->file('avatar')->store('avatars');
            $data = $request->safe()->merge(['avatar' => $path]);
        } else {
            $data = $request->safe();
        }

        $avatar->update($data->all());

        return $avatar;
    }
}
