<?php

namespace App\Repositories;

use App\Models\Polo;
use App\Contracts\RepositoryInterface;

class PoloRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(Polo $polo)
    {
        parent::__construct($polo);
    }
}
