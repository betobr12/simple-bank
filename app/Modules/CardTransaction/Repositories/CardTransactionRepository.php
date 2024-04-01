<?php

namespace App\Modules\CardTransaction\Repositories;

use App\CardTransaction;
use App\Repository\Eloquent\BaseRepository\BaseRepository;
use App\Modules\CardTransaction\Repositories\Contracts\CardTransactionRepositoryInterface;

class CardTransactionRepository extends BaseRepository implements CardTransactionRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new CardTransaction());
    }

    /**
     * @param $data
     */
    protected function parameters($data)
    {
        $this->model->id = $data->id ?? null;
        $this->model->card_id = $data->card_id ?? null;
        $this->model->account_id = $data->account_id ?? null;
        $this->model->user_id = $data->user_id ?? null;
        $this->model->number_card = $data->number_card ?? null;
        $this->model->description = $data->description ?? null;
        $this->model->onlyActive = $data->onlyActive ?? 1;
        $this->model->start_date = $data->start_date ?? null;
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

    /**
     * @param object $data
     * @return mixed
     */
    public function getCardTransactions(object $data)
    {
        $this->parameters($data);
        return $this->model->getCardTransactions() ?? [];
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function firstCardTransactions(object $data)
    {
        $this->parameters($data);
        return $this->model->firstCardTransactions() ?? [];
    }

}
