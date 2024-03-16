<?php

namespace App\Repositories;

use App\Models\TextDocument;
use App\Contracts\RepositoryInterface;

class TextDocumentRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(TextDocument $model)
    {
        parent::__construct($model);
    }
}
