<?php

namespace App\Repositories;

use App\Models\Teaching;
use App\Contracts\RepositoryInterface;

class TeachingRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(Teaching $teaching)
    {
        parent::__construct($teaching);
    }
}
