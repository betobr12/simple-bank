<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Card extends Model
{
    protected $table = 'cards';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'account_id',
        'number_card',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getCard()
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
        ')->when($this->account_id, function($query, $account_id){
            return $query->where('crd.account_id', '=', $account_id);
        })
        ->first();
    }
}
