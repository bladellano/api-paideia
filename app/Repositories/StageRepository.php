<?php

namespace App\Repositories;

use App\Models\Stage;
use App\Contracts\RepositoryInterface;

class StageRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(Stage $stage)
    {
        parent::__construct($stage);
    }
}
