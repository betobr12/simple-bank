<?php

namespace App\Repository\Eloquent\BaseRepository\Contracts;

interface BaseRepositoryInterface
{
    public function all();

    /**
     * @param int $id
     */
    public function find(int $id);

    /**
     * @param $data
     */
    public function create($data);

    /**
     * @param $id
     * @param array $data
     */
    public function update($id, array $data);

    /**
     * @param int $id
     */
    public function delete(int $id);
}
