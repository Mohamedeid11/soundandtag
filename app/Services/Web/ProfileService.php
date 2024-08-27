<?php

namespace App\Services\Web;

use App\Models\Record;
use App\Models\RecordType;
use App\Repositories\Interfaces\RecordRepositoryInterface;
use App\Repositories\Interfaces\RecordTypeRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Traits\SaveRecordTrait;
use App\Services\Traits\UserValidityTrait;
use Doctrine\DBAL\Driver\Middleware\AbstractConnectionMiddleware;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;


class ProfileService
{
    use SaveRecordTrait;
    use UserValidityTrait;

    private $userRepository;
    private $recordTypeRepository;
    private $recordRepository;
    public function __construct(
        UserRepositoryInterface $userRepository,
        RecordTypeRepositoryInterface $recordTypeRepository,
        RecordRepositoryInterface $recordRepository
    ) {
        $this->userRepository = $userRepository;
        $this->recordTypeRepository = $recordTypeRepository;
        $this->recordRepository = $recordRepository;
    }

    public function checkLength($first , $second  , $data){

        $combinedLength = strlen(

            $data . ' ' . $first . ' ' . $second

        );

        if ($combinedLength > 30) {

            return false;

        }

        return true;
    }

    public function saveRecord($user, array $data)
    {
        $record = $user->records()->where(['record_type_id' => $data['record_type_id']])->first();

        $file_path = Arr::has($data, 'record_data') && $data['record_data'] ? $this->saveRecordFile($data['record_data']) : $record->file_path;


        $record_type = RecordType::find($data['record_type_id']);

        if ($record_type->type_order == 0) {

            $validLength = $this->checkLength($user->middle_name , $user->last_name , $data['text_representation']);

            if (!$validLength){ return false; }

            $user->update([ 'name' => $data['text_representation'] ]);

        } elseif ($record_type->type_order == 1){

            $validLength = $this->checkLength($user->name , $user->last_name , $data['text_representation']);

            if (!$validLength){ return false; }

            $user->update(['middle_name' => $data['text_representation'] ]);

        } elseif ($record_type->type_order == 2){

            $validLength = $this->checkLength($user->name , $user->middle_name , $data['text_representation']);

            if (!$validLength){ return false; }

            $user->update([ 'last_name' => $data['text_representation'] ]);

        }

        if(!isset($record)) {

            $record = $this->recordRepository->create([
                'user_id' => $user->id,
                'record_type_id' => $data['record_type_id'],
                'text_representation' => $data['text_representation'],
                'file_path' => $file_path,
            ]);

        } else {

            if (Arr::has($data, 'record_data') && $data['record_data']) {
                $this->deleteRecordFile($record);
            }

            $record->update([
                'record_type_id' => $data['record_type_id'],
                'text_representation' => $data['text_representation'],
                'file_path' => $file_path,
            ]);

        }

        return $record;
    }


    public function getProfile($username)
    {
        $user = $this->userRepository->getBy('username', $username);
        if ($user && $this->getGeneralValidity($user) && !$user->hidden) {
            return collect(['user' => $user, 'records' => $this->getRecords($user)]);
        }
        return null;
    }
    public function getPublicStatus($user)
    {
        return (bool) $this->userRepository->all(true)->public()->where(['id' => $user->id])->first();
    }

    public function getUser($username)
    {
        return $this->userRepository->getBy('username', $username);
    }
}
