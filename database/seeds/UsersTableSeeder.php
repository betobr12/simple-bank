<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'          => "roberto",
            'email'         => 'roberto@gmail.com',
            'cpf'           => '80715999052',
            'phone'         => '11955220099',
            'password'      => Hash::make('admin'),
            'created_at'    => \Carbon\Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name'          => "joao",
            'email'         => 'joao@gmail.com',
            'cpf'           => '64183213088',
            'phone'         => '11944567788',
            'password'      => Hash::make('admin'),
            'created_at'    => \Carbon\Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name'          => "mateus",
            'email'         => 'mateus@gmail.com',
            'cpf'           => '57935027005',
            'phone'         => '11976234567',
            'password'      => Hash::make('admin'),
            'created_at'    => \Carbon\Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name'          => "victor",
            'email'         => 'victor@gmail.com',
            'cpf'           => '73319131079',
            'phone'         => '1133467788',
            'password'      => Hash::make('admin'),
            'created_at'    => \Carbon\Carbon::now(),
        ]);
    }
}
