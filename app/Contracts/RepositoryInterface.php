<?php

namespace App\Contracts;

interface RepositoryInterface
{
    public function getAll($request, $with);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
