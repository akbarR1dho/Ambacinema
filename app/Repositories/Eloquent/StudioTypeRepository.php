<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\StudioTypeRepositoryInterface;
use App\Models\StudioType;

class StudioTypeRepository extends BaseRepository implements StudioTypeRepositoryInterface
{
    public function __construct(StudioType $model)
    {
        parent::__construct($model);
    }

    public function getStudioTypesDatatable()
    {
        return $this->model->select('studio_types.*');
    }
}
