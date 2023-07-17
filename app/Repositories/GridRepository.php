<?php

namespace App\Repositories;

use App\Models\Grid;
use App\Contracts\RepositoryInterface;

class GridRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(Grid $grid)
    {
        parent::__construct($grid);
    }
}
