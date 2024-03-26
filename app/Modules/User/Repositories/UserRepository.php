<?php

namespace App\Modules\User\Repositories;

use App\User;
use App\Repository\Eloquent\BaseRepository\BaseRepository;
use App\Modules\User\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new User());
    }

    /**
     * @param $data
     */
    protected function parameters($data)
    {
        $this->model->id = $data->id ?? null;
        $this->model->name = $data->name ?? null;
        $this->model->email = $data->email ?? null;
        $this->model->phone = $data->phone ?? null;
        $this->model->cpf = $data->cpf ?? null;
        $this->model->userValidation = $data->userValidation ?? null;
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

    /**
     * @param object $data
     * @return mixed
     */
    public function getUser(object $data)
    {
        $this->parameters($data);
        return $this->model->getUser() ?? [];
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function firstUser(object $data)
    {
        $this->parameters($data);
        return $this->model->firstUser() ?? [];
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function getUserPaginate(object $data)
    {
        $this->parameters($data);
        return $this->model->getPaginate() ?? [];
    }
}
