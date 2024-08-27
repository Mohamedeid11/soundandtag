<?php
namespace App\Repositories\Eloquent;
use App\Models\TryingUser;
use App\Repositories\Interfaces\TryingUserRepositoryInterface;

class TryingUserRepository extends BaseRepository implements TryingUserRepositoryInterface{
    public function __construct(TryingUser $model){
        $this->model = $model;
    }

}
        