<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * @var string
     */
    protected $table = 'accounts';
    /**
     * @var mixed
     */
    public $timestamps = true;
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'cpf_cnpj',
        'agency',
        'type_id',
        'number_account',
        'digit',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->simpleConsult()->get();
    }

    /**
     * @return mixed
     */
    public function getAccount()
    {
        return $this->simpleConsult()->get();
    }

    /**
     * @return mixed
     */
    public function firstAccount()
    {
        return $this->simpleConsult()->first();
    }

    /**
     * @return mixed
     */
    public function simpleConsult()
    {
        return DB::table('accounts as accnts')
            ->leftJoin('users as usrs', 'usrs.id', '=', 'accnts.user_id')
            ->leftJoin('types as typs', 'typs.id', '=', 'accnts.type_id')
            ->leftJoin('companies as compns', 'compns.account_id', '=', 'accnts.id')
            ->leftJoin('people as pepl', 'pepl.account_id', '=', 'accnts.id')
            ->selectRaw('
                accnts.id,
                accnts.cpf_cnpj,
                accnts.user_id,
                accnts.agency,
                accnts.number_account,
                accnts.digit,
                usrs.name as user_name,
                typs.description as type_description,
                pepl.name as pepl_name,
                compns.social_reason as social_reason,
                compns.fantasy_name as fantasy_name
            ')
            ->when($this->id, function ($query, $id) {
                return $query->where('accnts.id', '=', $id);
            })
            ->when($this->account_id, function ($query, $account_id) {
                return $query->where('accnts.id', '=', $account_id);
            })
            ->when($this->user_id, function ($query, $user_id) {
                return $query->where('accnts.user_id', '=', $user_id);
            })
            ->when($this->onlyActive, function ($query) {
                return $query->whereNull('accnts.deleted_at', );
            });
    }
}
