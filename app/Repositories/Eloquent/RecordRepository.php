<?php
namespace App\Repositories\Eloquent;
use App\Models\Record;
use App\Repositories\Interfaces\RecordRepositoryInterface;

class RecordRepository extends BaseRepository implements RecordRepositoryInterface{
    public function __construct(Record $model){
        $this->model = $model;
    }

}
        