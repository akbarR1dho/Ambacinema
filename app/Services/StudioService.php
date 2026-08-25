<?php

namespace App\Services;

use App\Repositories\Interfaces\StudioRepositoryInterface;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use Illuminate\Support\Facades\DB;

class StudioService
{
    protected $studioRepo;
    protected $seatRepo;

    public function __construct(StudioRepositoryInterface $studioRepo, SeatRepositoryInterface $seatRepo)
    {
        $this->studioRepo = $studioRepo;
        $this->seatRepo = $seatRepo;
    }

    public function createStudioWithSeats(array $data)
    {
        return DB::transaction(function () use ($data) {
            $studio = $this->studioRepo->create([
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
}
