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
    
    public function createWithSeats(array $data)
    {
        return DB::transaction(function () use ($data) {
            $studio = $this->create([
                'name' => $data['name'],
                'total_seats' => $data['total_seats'],
                'studio_type_id' => $data['studio_type_id'] ?? null
            ]);
            
            $seats = [];
            for ($i = 1; $i <= $data['total_seats']; $i++) {
                $row = chr(65 + floor(($i - 1) / 10)); // A, B, C...
                $col = (($i - 1) % 10) + 1;
                $seats[] = [
                    'id' => (string) \Symfony\Component\Uid\Uuid::v7(),
                    'studio_id' => $studio->id,
                    'seat_number' => $row . str_pad($col, 2, '0', STR_PAD_LEFT),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            $this->seatRepo->insertSeats($seats);
            
            return $studio;
        });
    }

    public function findWithRelations($id)
    {
        return $this->model->with('studioType')->findOrFail($id);
    }
}
