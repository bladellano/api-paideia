<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll($request, $with = [])
    {
        $query = $this->model::query();

        if($with)
            $query->with($with);

        $search   = $request->input('search');
        $sortBy   = $request->input('sortBy');
        $sortDesc = $request->input('sortDesc');

        $perPage = $request->input('perPage') ?? 10;

        $page = $request->input('page') ?? 1;

        if ($search)
            $query->where('name', 'like', "%$search%");

        if ($sortBy)
            $query->orderBy($sortBy, $sortDesc ? 'desc' : 'asc');

        return $query->paginate($perPage, ['*'], 'page', $page);

    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = $this->find($id);

        if ($model):
            $model->update($data);
            return $model;
        endif;

        return null;
    }

    public function delete(int $id)
    {
        $model = $this->find($id);

        if ($model):
            $model->delete();
            return true;
        endif;

        return false;
    }

}
