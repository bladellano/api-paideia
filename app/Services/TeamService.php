<?php

namespace App\Services;

use App\Repositories\TeamRepository;

class TeamService
{
    private $repository;

    public function __construct(TeamRepository $repository)
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
        $team = $this->repository->find($id);
        $team->grid;
        $team->polo;
        $team->registrations;
        return [$team];
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
