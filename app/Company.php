<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * @var string
     */
    protected $table = 'companies';
    /**
     * @var mixed
     */
    public $timestamps = true;
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'account_id',
        'user_id',
        'social_reason',
        'fantasy_name',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->simpleConsult()->get();
    }

    /**
     * @return mixed
     */
    public function firstCompany()
    {
        return $this->simpleConsult()->first();
    }

    /**
     * @return mixed
     */
    public function getCompanyPaginate()
    {
        return $this->simpleConsult()->paginate(10);
    }

    /**
     * @return mixed
     */
    public function simpleConsult()
    {
        return DB::table('companies as company')
            ->selectRaw("
                company.id,
                company.account_id,
                company.user_id,
                company.social_reason,
                company.fantasy_name,
                company.created_at,
                company.updated_at,
                company.deleted_at
            ")
            ->when($this->company_id, function ($query, $company_id) {
                return $query->where('company.id', '=', $company_id);
            })
            ->when($this->id, function ($query, $id) {
                return $query->where('company.id', '=', $id);
            })
            ->when($this->onlyActive, function ($query) {
                return $query->where('company.deleted_at');
            })
            ->when($this->account_id, function ($query, $accountId) {
                return $query->where('company.account_id', '=', $accountId);
            });

    }

}
