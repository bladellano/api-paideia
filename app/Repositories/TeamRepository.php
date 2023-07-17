<?php

namespace App\Repositories;

use App\Models\Team;
use App\Contracts\RepositoryInterface;

class TeamRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(Team $team)
    {
        parent::__construct($team);
    }
}
