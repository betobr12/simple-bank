<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeTransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type_transactions')->insert([
            'description' => "Pagamento de Conta",
            'created_at' => \Carbon\Carbon::now()
        ]);

        DB::table('type_transactions')->insert([
            'description' => "Depósito",
            'created_at' => \Carbon\Carbon::now()
        ]);

        DB::table('type_transactions')->insert([
            'description' => "Transferência",
            'created_at' => \Carbon\Carbon::now()
        ]);

        DB::table('type_transactions')->insert([
            'description' => "Compra (Crédito)",
            'created_at' => \Carbon\Carbon::now()
        ]);

        DB::table('type_transactions')->insert([
            'description' => "Recarga de Celular",
            'created_at' => \Carbon\Carbon::now()
        ]);
    }
}
