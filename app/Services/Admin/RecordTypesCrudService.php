<?php
namespace App\Services\Admin;

use App\Repositories\Interfaces\RecordTypeRepositoryInterface;
use Illuminate\Support\Arr;

class RecordTypesCrudService
{
    private $recordTypeRepository;

    public function __construct(RecordTypeRepositoryInterface $recordTypeRepository)
    {
        $this->recordTypeRepository = $recordTypeRepository;
    }

    public function getAllRecordTypes()
    {
        return $this->recordTypeRepository->paginate(100);
    }

    public function createRecordType(array $data)
    {
        $data =  Arr::only($data, ['name', 'user_id', 'type_order', 'account_type', 'required']);
        $data['user_id'] = $data['user_id'] == '0' ? null :$data['user_id'];
        $data['required'] = Arr::has($data, 'required');
        $this->recordTypeRepository->create($data);
        session()->flash('success', __('admin.success_add', ['thing'=>__('global.record_type')]));
    }

    public function updateRecordType( $record_type, array $data)
    {
        $data =  Arr::only($data, ['name', 'user_id', 'type_order', 'account_type', 'required']);
        $data['user_id'] = $data['user_id'] == '0' ? null :$data['user_id'];
        $data['required'] = Arr::has($data, 'required');
        $record_type->update($data);
        session()->flash('success', __('admin.success_edit', ['thing'=>__('global.record_type')]));
    }

    public function deleteRecordType($record_type, array $data)
    {
        $record_type->delete();
        session()->flash('success', __('admin.success_delete', ['thing'=>__('global.record_type')]) );
    }

    public function batchDeleteRecordTypes(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $wrong_role = $this->recordTypeRepository->all(true)->whereIn('id', $ids)->where(['is_system'=>true])->first();
        if($wrong_role){
            session()->flash('error', __('admin.no_delete_sys_record_types') );
        }
        else {
            $this->recordTypeRepository->deleteMany($ids);
            session()->flash('success', __('admin.success_delete', ['thing' => __('global.record_type')]));
        }
    }

}
