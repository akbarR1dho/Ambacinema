<?php

namespace App\Repositories\Interfaces;

interface StudioRepositoryInterface extends BaseRepositoryInterface
{
    public function getStudiosDatatable(array $filters = []);
    public function findWithRelations($id);
}
