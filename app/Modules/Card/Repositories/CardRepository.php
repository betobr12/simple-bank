<?php

namespace App\Modules\Card\Repositories;

use App\Card;
use App\Repository\Eloquent\BaseRepository\BaseRepository;
use App\Modules\Card\Repositories\Contracts\CardRepositoryInterface;

class CardRepository extends BaseRepository implements CardRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Card());
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
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function getCard(object $data)
    {
        $this->parameters($data);
        return $this->model->getCard() ?? [];
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
    public function firstCard(object $data)
    {
        $this->parameters($data);
        return $this->model->firstCard() ?? [];
    }

}
