<?php

namespace App\Services;

use App\Repositories\CourseRepository;

class CourseService
{
    private $repository;

    public function __construct(CourseRepository $repository)
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
        $course = $this->repository->find($id);
        $course->teaching;
        return [$course];
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
