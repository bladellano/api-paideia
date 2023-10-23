<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;

class UserService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($request, $with = [])
    {
        return $this->repository->getAll($request, $with);
    }


    public function find(int $id)
    {
        return [$this->repository->find($id)];
    }

    public function delete(int $id)
    {
        $this->repository->delete($id);
    }

    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        if(isset($data['password']))
            $data['password'] = Hash::make($data['password']);

        return $this->repository->update($id, $data);
    }
}
