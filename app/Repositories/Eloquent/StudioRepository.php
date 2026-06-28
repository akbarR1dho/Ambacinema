<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\StudioRepositoryInterface;
use App\Models\Studio;

class StudioRepository extends BaseRepository implements StudioRepositoryInterface
{
    public function __construct(Studio $model)
    {
        parent::__construct($model);
    }

    public function getStudiosDatatable()
    {
        return $this->model->with('studioType')->select('studios.*');
    }
    
    public function findWithRelations($id)
    {
        return $this->model->with('studioType')->findOrFail($id);
    }
}
