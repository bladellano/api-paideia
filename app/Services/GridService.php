<?php

namespace App\Services;

use App\Models\Team;
use App\Repositories\GridRepository;

class GridService
{
    private $repository;

    public function __construct(GridRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($request, $with = [])
    {
        return $this->repository->getAll($request, $with);
    }

    public function delete(int $id)
    {
        $this->repository->delete($id);
    }
}
