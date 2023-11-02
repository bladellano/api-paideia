<?php

namespace App\Services;

use App\Repositories\StudentRepository;

class StudentService
{
    private $repository;

    public function __construct(StudentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($request, $with = [])
    {
        return $this->repository->getAll($request, $with);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function find(int $id)
    {
        $student = $this->repository->find($id);
        $student->teams;
        $student->documents;
        return [$student];
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        $this->repository->delete($id);
    }
}
