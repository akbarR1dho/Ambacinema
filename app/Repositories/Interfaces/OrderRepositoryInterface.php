<?php

namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function getOrdersForUserWithCursor($userId, $filter, $perPage);
    public function checkSeatsAvailability($showtimeId, array $seatIds);
    public function attachSeats($order, array $pivotData);
    public function getOrdersDatatable();
    public function getChartData($filters);
    public function findUserOrderWithRelations($id, $userId);
    public function findAdminOrderWithRelations($id);
}
