<?php

namespace App\Repositories;

use App\Models\ServiceType;
use App\Contracts\RepositoryInterface;

class ServiceTypeRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(ServiceType $model)
    {
        parent::__construct($model);
    }
}
