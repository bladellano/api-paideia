<?php

namespace App\Repositories;

use App\Models\PaymentType;
use App\Contracts\RepositoryInterface;

class PaymentTypeRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(PaymentType $model)
    {
        parent::__construct($model);
    }
}
