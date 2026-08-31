<?php

namespace App\Services\Admin\User;

use App\Models\User;
use App\Services\Base\BaseService;

class UserService extends BaseService
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function paginate(array $with = [], array $where = [], int $perPage = 10)
    {
        $with = array_merge($with, ['activeSubscriptions']);

        return parent::paginate($with, $where, $perPage);
    }

    public function search(string $query, int $perPage = 10, array $where = []): mixed
    {
        $search = $this->model::search($query);

        if (! empty($where)) {
            foreach ($where as $field => $value) {
                $search->where($field, $value);
            }
        }

        $search->query(function ($builder) {
            $builder->with('activeSubscriptions');
        });

        return $search->paginate($perPage);
    }
}
