<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeTransaction extends Model
{
    protected $table = 'type_transactions';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
