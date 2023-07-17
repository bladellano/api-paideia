<?php

namespace App\Repositories;

use App\Models\GridTemplate;
use App\Contracts\RepositoryInterface;

class GridTemplateRepository extends AbstractRepository implements RepositoryInterface
{
    public function __construct(GridTemplate $gridTemplate)
    {
        parent::__construct($gridTemplate);
    }
}
