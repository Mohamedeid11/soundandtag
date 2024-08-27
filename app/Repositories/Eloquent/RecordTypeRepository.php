<?php
namespace App\Repositories\Eloquent;
use App\Models\RecordType;
use App\Repositories\Interfaces\RecordTypeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RecordTypeRepository extends BaseRepository implements RecordTypeRepositoryInterface{
    public function __construct(RecordType $model){
        $this->model = $model;
    }

    public function getGeneralRecords($account_type): Collection
    {
        return $account_type == 'personal' ?
            $this->model->orderBy('type_order')->general()->personal()->get() : ($account_type == 'employee'?
            $this->model->orderBy('type_order')->general()->employee()->get():$this->model->orderBy('type_order')->general()->corporate()->get());
    }
}
