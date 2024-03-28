<?php

namespace App\Modules\Transaction\Repositories;

use App\Transaction;
use App\Repository\Eloquent\BaseRepository\BaseRepository;
use App\Modules\Transaction\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Transaction());
    }

    /**
     * @param $data
     */
    protected function parameters($data)
    {
        $this->model->id = $data->id ?? null;
        $this->model->account_id = $data->account_id ?? null;
        $this->model->user_id = $data->user_id ?? null;
        $this->model->type_transaction_id = $data->type_transaction_id ?? null;
        $this->model->value = $data->value ?? null;
        $this->model->balance = $data->balance ?? null;
        $this->model->document = $data->document ?? null;
        $this->model->number_card = $data->number_card ?? null;
        $this->model->number_phone = $data->number_phone ?? null;
        $this->model->description = $data->description ?? null;
        $this->model->date = $data->date ?? null;
        $this->model->onlyActive = $data->onlyActive ?? 1;
        $this->model->start_date = $data->start_date ?? null;
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function getTransaction(object $data)
    {
        $this->parameters($data);
        return $this->model->getTransaction() ?? [];
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
    public function balance(object $data)
    {
        $this->parameters($data);
        return $this->model->balance() ?? [];
    }

}
