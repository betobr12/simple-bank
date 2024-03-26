<?php

namespace App\Repository\Eloquent\BaseRepository;

use Illuminate\Database\Eloquent\Model;
use App\Repository\Eloquent\BaseRepository\Contracts\BaseRepositoryInterface;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var mixed
     */
    public $model;

    /**
     * @param Model $model
     */
    public function __construct(
        Model $model
    ) {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        if ($record = $this->model->find($id)) {
            $record->deleted_at = null;
            $record->update($data);
            return $record;
        }
        return [];
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        if ($record = $this->model->whereNull('deleted_at')->find($id)) {
            $record->deleted_at = \Carbon\Carbon::now() ?? null;
            $record->update((array) $record);
            return $record;
        }
        return [];
    }
}
