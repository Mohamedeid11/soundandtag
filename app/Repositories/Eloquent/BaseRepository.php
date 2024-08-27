<?php
namespace App\Repositories\Eloquent;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseRepository implements BaseRepositoryInterface {
    protected $model;
    public function all($lazy=false): object
    {
        return $lazy ? $this->model : $this->model->get();
    }

    public function get($id): ?Model
    {
        return $this->model->where(['id'=>$id])->first();
    }
    public function getBy($by, $value): ?Model
    {
        return $this->model->where([$by=>$value])->first();
    }

    public function create($data): Model
    {
        $item = $this->model->create($data);
        return $item;
    }

    public function update($data): Model
    {
        $item = $this->model->update($data);
        return $item;
    }

    public function delete($id): bool
    {
        return $this->model->delete($id);
    }

    public function paginate($per_page): LengthAwarePaginator
    {
        return $this->model->paginate($per_page);
    }

    public function getMany($ids): Collection
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    public function deleteMany($ids): bool
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
}
