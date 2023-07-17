<?php

namespace App\Repositories;

use App\Models\Course;
use App\Contracts\RepositoryInterface;

class CourseRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(Course $course)
    {
        parent::__construct($course);
    }
}
