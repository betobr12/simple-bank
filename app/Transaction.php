<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    public $timestamps = true;
    protected $fillable = [

        'id',
        'account_id',
        'user_id',
        'user_id',
        'type_transaction_id',
        'value',
        'balance',
        'document',
        'number_card',
        'number_phone',
        'description',
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
