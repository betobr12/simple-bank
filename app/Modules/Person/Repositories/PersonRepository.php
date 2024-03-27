<?php

namespace App\Modules\Person\Repositories;

use App\Person;
use App\Repository\Eloquent\BaseRepository\BaseRepository;
use App\Modules\Person\Repositories\Contracts\PersonRepositoryInterface;

class PersonRepository extends BaseRepository implements PersonRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Person());
    }

    /**
     * @param $data
     */
    protected function parameters($data)
    {
        $this->model->id = $data->id ?? null;
        $this->model->account_id = $data->account_id ?? null;
        $this->model->user_id = $data->user_id ?? null;
        $this->model->name = $data->name ?? null;
        $this->model->onlyActive = $data->onlyActive ?? 1;
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function get(object $data)
    {
        $this->parameters($data);
        return $this->model->get() ?? [];
    }

}
