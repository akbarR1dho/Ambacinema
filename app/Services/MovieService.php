<?php

namespace App\Services;

use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Exception;

class MovieService
{
    protected $movieRepo;

    public function __construct(MovieRepositoryInterface $movieRepo)
    {
        $this->movieRepo = $movieRepo;
    }

    public function createMovie(array $data)
    {
        if (isset($data['poster_path'])) {
            $data['poster'] = $data['poster_path'];
            unset($data['poster_path']);
        }

        try {
            return $this->movieRepo->create($data);
        } catch (Exception $e) {
            if (isset($data['poster']) && Storage::disk('s3')->exists($data['poster'])) {
                Storage::disk('s3')->delete($data['poster']);
            }
            throw $e;
        }
    }

    public function updateMovie(string $id, array $data)
    {
        $movie = $this->movieRepo->find($id);

        if (isset($data['poster_path'])) {
            $data['poster'] = $data['poster_path'];
            unset($data['poster_path']);
        }

        try {
            $result = $this->movieRepo->update($id, $data);

            // Delete old poster if new poster is successfully saved and old poster exists
            if (isset($data['poster']) && $movie->poster && Storage::disk('s3')->exists($movie->poster)) {
                Storage::disk('s3')->delete($movie->poster);
            }

            return $result;
        } catch (Exception $e) {
            // Delete the newly uploaded file if database update fails
            if (isset($data['poster']) && Storage::disk('s3')->exists($data['poster'])) {
                Storage::disk('s3')->delete($data['poster']);
            }
            throw $e;
        }
    }
}
