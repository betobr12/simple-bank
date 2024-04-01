<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    /**
     * @var string
     */
    protected $table = 'cards';
    /**
     * @var mixed
     */
    public $timestamps = true;
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'account_id',
        'number_card',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @return mixed
     */
    public function getCard()
    {
        return $this->simpleCard()
            ->get();
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->simpleCard()
            ->get();
    }

    /**
     * @return mixed
     */
    public function firstCard()
    {
        return $this->simpleCard()
            ->first();
    }

    /**
     * @return mixed
     */
    public function simpleCard()
    {
        return DB::table('cards as crd')
            ->selectRaw('
            crd.id,
            crd.user_id,
            crd.account_id,
            crd.number_card,
            crd.created_at,
            crd.updated_at,
            crd.deleted_at
        ')
            ->when($this->id, function ($query, $id) {
                return $query->where('crd.id', '=', $id);
            })
            ->when($this->card_id, function ($query, $card_id) {
                return $query->where('crd.id', '=', $card_id);
            })
            ->when($this->user_id, function ($query, $user_id) {
                return $query->where('crd.user_id', '=', $user_id);
            })
            ->when($this->number_card, function ($query, $number_card) {
                return $query->where('crd.number_card', '=', $number_card);
            })
            ->when($this->onlyActive, function ($query) {
                return $query->whereNull('crd.deleted_at');
            })
            ->when($this->account_id, function ($query, $account_id) {
                return $query->where('crd.account_id', '=', $account_id);
            });

    }
}
