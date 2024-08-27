<?php
namespace App\Services\Admin;

use App\Models\Record;
use App\Repositories\Interfaces\RecordRepositoryInterface;
use App\Repositories\Interfaces\RecordTypeRepositoryInterface;
use App\Services\Traits\SaveRecordTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class RecordsCrudService {
    use SaveRecordTrait;
    private $recordRepository;
    private $recordTypeRepository;

    public function __construct(RecordRepositoryInterface $recordRepository, RecordTypeRepositoryInterface $recordTypeRepository){
        $this->recordRepository = $recordRepository;
        $this->recordTypeRepository = $recordTypeRepository;
    }

    public function getAllRecords()
    {
        return $this->recordRepository->paginate(100);
    }

    public function createRecord(array $data)
    {
        $contents = $data['file_path'];
        $data = Arr::only($data, ['user_id', 'record_type_id', 'text_representation']);
        $name = $this->saveRecordFile($contents);
        $data['file_path'] = $name;
        $this->recordRepository->create($data);
        session()->flash('success', __('admin.success_add', ['thing'=>__('global.record')]) );
    }

    public function updateRecord(Record $record, array $data)
    {
        $contents = $data['file_path'] ?? null;
        $data = Arr::only($data, ['user_id', 'record_type_id', 'text_representation']);
        if($contents){
            $data['file_path'] = $this->saveRecordFile($contents);
            $this->deleteRecordFile($record);
        }
        $record->update($data);
        session()->flash('success', __('admin.success_edit', ['thing'=>__('global.record')]) );
    }

    public function deleteRecord(Record $record)
    {
        $this->deleteRecordFile($record);
        $record->delete();
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.record')]) );
    }

    public function batchDeleteRecords(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $target_records = $this->recordRepository->getMany($ids);
        if(count($target_records)>0) {
            Storage::disk('public')->delete(...$target_records->pluck('file_path'));
        }
        $this->recordRepository->deleteMany($ids);
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.record')]) );
    }

}
