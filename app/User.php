<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{

    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone','cpf'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function get() {
        return DB::table('users as usr')
        ->selectRaw("
            usr.name,
            usr.email,
            usr.phone,
            usr.cpf,
            usr.created_at
        ")
        ->when($this->name, function($query, $name){
            return $query->where('usr.name', 'LIKE', "%$name%");
        })
        ->when($this->cpf, function($query, $cpf){
            return $query->where('usr.cpf', 'LIKE', "%$cpf%");
        })
        ->get();
    }
}
