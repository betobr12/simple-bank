<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'types';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
