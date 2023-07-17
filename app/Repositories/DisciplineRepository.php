<?php

namespace App\Repositories;

use App\Models\Discipline;
use App\Contracts\RepositoryInterface;

class DisciplineRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(Discipline $discipline)
    {
        parent::__construct($discipline);
    }
}
