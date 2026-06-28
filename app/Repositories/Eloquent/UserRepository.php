<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
