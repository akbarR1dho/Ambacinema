<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\StudioRepositoryInterface;
use App\Models\Studio;

use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\SeatRepositoryInterface;

class StudioRepository extends BaseRepository implements StudioRepositoryInterface
{
    protected $seatRepo;

    public function __construct(Studio $model, SeatRepositoryInterface $seatRepo)
    {
        parent::__construct($model);
        $this->seatRepo = $seatRepo;
    }

    public function getStudiosDatatable(array $filters = [])
    {
        $query = $this->model->with('studioType')->select('studios.*');

        if (!empty($filters['search'])) {
            $query->whereRaw('LOWER(name) like ?', ['%' . strtolower($filters['search']) . '%']);
        }

        if (array_key_exists('type_filter', $filters) && $filters['type_filter'] !== null) {
            if ($filters['type_filter'] == 'regular') {
                $query->whereNull('studio_type_id');
            } else {
                $query->where('studio_type_id', $filters['type_filter']);
            }
        }

        return $query;
    }

    public function findWithRelations($id)
    {
        return $this->model->with('studioType')->findOrFail($id);
    }
}
