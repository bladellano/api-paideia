<?php

namespace App\Repositories;

use App\Models\User;
use App\Contracts\RepositoryInterface;

class UserRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}
