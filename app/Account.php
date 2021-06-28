<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    protected $table = 'accounts';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'cpf_cnpj',
        'agency',
        'type_id',
        'number_account',
        'digit',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function get()
    {
        return DB::table('accounts   as accnts')
        ->leftJoin('users           as usrs',   'usrs.id',          '=','accnts.user_id')
        ->leftJoin('types           as typs',   'typs.id',          '=','accnts.type_id')
        ->leftJoin('companies       as compns', 'compns.account_id','=','accnts.id')
        ->leftJoin('people          as pepl',   'pepl.account_id',  '=','accnts.id')
        ->selectRaw('
            accnts.id,
            accnts.cpf_cnpj,
            accnts.agency,
            accnts.number_account,
            accnts.digit,
            usrs.name               as user_name,
            typs.description        as type_description,
            pepl.name               as pepl_name,
            compns.social_reason    as social_reason,
            compns.fantasy_name     as fantasy_name
        ')
        ->when($this->account_id, function ($query, $account_id) {
            return $query->where('accnts.id', '=',  $account_id);
        })
        ->first();
    }
}
