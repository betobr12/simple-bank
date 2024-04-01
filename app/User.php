<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'cpf'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->simpleConsult()
            ->when($this->name, function ($query, $name) {
                return $query->where('usr.name', 'LIKE', "%$name%");
            })
            ->when($this->cpf, function ($query, $cpf) {
                return $query->where('usr.cpf', 'LIKE', "%$cpf%");
            })

            ->get();
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->simpleConsult()
            ->when($this->name, function ($query, $name) {
                return $query->where('usr.name', '=', $name);
            })
            ->when($this->cpf, function ($query, $cpf) {
                return $query->where('usr.cpf', '=', $cpf);
            })
            ->get();
    }

    /**
     * @return mixed
     */
    public function firstUser()
    {
        return $this->simpleConsult()
            ->when($this->name, function ($query, $name) {
                return $query->where('usr.name', 'LIKE', "%$name%");
            })
            ->when($this->cpf, function ($query, $cpf) {
                return $query->where('usr.cpf', 'LIKE', "%$cpf%");
            })
            ->first();
    }

    /**
     * @return mixed
     */
    public function simpleConsult()
    {
        return DB::table('users as usr')
            ->selectRaw("
                usr.id,
                usr.name,
                usr.email,
                usr.phone,
                usr.cpf,
                usr.created_at
            ")
            ->when($this->id, function ($query, $id) {
                return $query->where('usr.id', '=', $id);
            })
            ->when($this->userValidation, function ($query, $userValidation) {
                return $query->where('email', '=', $userValidation['email'])
                    ->orWhere('cpf', '=', $userValidation['cpf']);
            });
    }
}
