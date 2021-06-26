<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'account_id',
        'user_id',
        'social_reason',
        'fantasy_name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
