<?php
namespace App\Repositories\Interfaces;
use App\Models\RecordType;
use Illuminate\Database\Eloquent\Collection;


interface RecordTypeRepositoryInterface{
    public function getGeneralRecords($account_type): Collection;
}
