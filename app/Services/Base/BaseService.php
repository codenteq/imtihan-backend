<?php

namespace App\Services\Base;

class BaseService
{
    protected string $model;

    /**
     * @var string
     */
    public function __construct(string $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     */
    public function list(array $with = [], array $where = []): mixed
    {
        return $this->model::with($with)->where($where)->latest()->get();
    }

    /**
     * Display a listing paginated of the resource.
     */
    public function paginate(array $with = [], array $where = [], int $perPage = 10)
    {
        return $this->model::with($with)->where($where)->latest()->paginate($perPage);
    }

    /**
     * Display a listing paginated of the resource.
     */
    public function search(string $query, int $perPage = 10, array $where = []): mixed
    {
        return $this->model::search($query)->where($where)->paginate($perPage);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(object $request): object
    {
        return $this->model::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id, array $with = [], array $where = []): mixed
    {
        return $this->model::with($with)->where($where)->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(object $request, int $id, array $where = []): object
    {
        $this->model::where($where)->findOrFail($id)->update($request->validated());

        return $this->model::findOrFail($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, array $where = []): mixed
    {
        $model = $this->model::where($where)->findOrFail($id);

        $model->delete();

        return $model;
    }
}
