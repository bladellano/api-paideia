<?php

namespace App\Repositories;

use App\Models\Student;
use App\Contracts\RepositoryInterface;

class StudentRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(Student $student)
    {
        parent::__construct($student);
    }
}
