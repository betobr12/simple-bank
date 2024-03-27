<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    /**
     * @var string
     */
    protected $table = 'people';
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
        'name',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->simpleConsult()->first();
    }

    /**
     * @return mixed
     */
    public function simpleConsult()
    {
        return DB::table('people as people')
            ->selectRaw('
                people.account_id,
                people.user_id,
                people.name,
                people.created_at,
                people.deleted_at
            ')
            ->when($this->id, function ($query, $id) {
                return $query->where('people.id', '=', $id);
            })
            ->when($this->account_id, function ($query, $account_id) {
                return $query->where('people.account_id', '=', $account_id);
            })
            ->when($this->onlyActive, function ($query) {
                return $query->whereNull('people.deleted_at', );
            });
    }
}
