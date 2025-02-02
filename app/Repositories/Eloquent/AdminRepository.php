<?php
namespace App\Repositories\Eloquent;
use App\Models\Admin;
use App\Repositories\Interfaces\AdminRepositoryInterface;

class AdminRepository extends BaseRepository implements AdminRepositoryInterface{
    public function __construct(Admin $model){
        $this->model = $model;
    }
}
