<?php
namespace App\Repositories\Eloquent;
use App\Models\TryingRecord;
use App\Repositories\Interfaces\TryingRecordRepositoryInterface;

class TryingRecordRepository extends BaseRepository implements TryingRecordRepositoryInterface{
    public function __construct(TryingRecord $model){
        $this->model = $model;
    }

}
        