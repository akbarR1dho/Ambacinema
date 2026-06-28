<?php

namespace App\Repositories\Interfaces;

interface StudioRepositoryInterface extends BaseRepositoryInterface
{
    public function getStudiosDatatable();
    public function findWithRelations($id);
}
