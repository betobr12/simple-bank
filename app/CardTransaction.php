<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CardTransaction extends Model
{
    /**
     * @var string
     */
    protected $table = 'card_transactions';
    /**
     * @var mixed
     */
    public $timestamps = true;
    /**
     * @var array
     */
    protected $fillable = [
        'account_id',
        'user_id',
        'card_id',
        'value',
        'balance',
        'store',
        'date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @return mixed
     */
    public function balance()
    {
        if ($this->start_date == '') {
            return ['balance' => '0'];
        }
        // round(COALESCE(sum(COALESCE(card_transctns.value,0)),0),2) as balance //mysql
        return DB::table('card_transactions as card_transctns')
            ->selectRaw("
                ROUND(COALESCE(SUM(COALESCE(card_transctns.value,0)),0)::numeric,2) as balance
            ")
            ->whereNull('card_transctns.deleted_at')
            ->when($this->account_id, function ($query, $account_id) {
                return $query->where('card_transctns.account_id', '=', $account_id);
            })
            ->when($this->start_date, function ($query, $start_date) {
                return $query->where('card_transctns.date', '<', $start_date);
            })
            ->first();
    }

    /**
     * @return mixed
     */
    public function getCardTransactions()
    {
        return $this->simpleConsult()
            ->get();
    }

    /**
     * @return mixed
     */
    public function firstCardTransactions()
    {
        return $this->simpleConsult()
            ->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function simpleConsult()
    {
        return DB::table('card_transactions  as card_transctns')
            ->selectRaw('
                card_transctns.id,
                card_transctns.account_id,
                card_transctns.user_id,
                card_transctns.card_id,
                card_transctns.value,
                card_transctns.balance,
                card_transctns.store,
                card_transctns.date,
                card_transctns.created_at,
                card_transctns.updated_at,
                card_transctns.deleted_at
            ')
            ->when($this->account_id, function ($query, $account_id) {
                return $query->where('card_transctns.account_id', '=', $account_id);
            })
            ->when($this->user_id, function ($query, $user_id) {
                return $query->where('card_transctns.user_id', '=', $user_id);
            })
            ->when($this->card_id, function ($query, $card_id) {
                return $query->where('card_transctns.card_id', '=', $card_id);
            });

    }
}
