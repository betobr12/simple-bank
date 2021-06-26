<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'people';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'account_id',
        'user_id',
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
