<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('cpf_cnpj',14)->nullable()->default(null);
            $table->string('agency')->nullable()->default(null);
            $table->unsignedBigInteger('type_id')->nullable()->default(null);
            $table->foreign('type_id')->references('id')->on('types')->onDelete('set null');
            $table->string('number_account')->nullable()->default(null);
            $table->string('digit',5)->nullable()->default(null);
            $table->timestamps();
            $table->datetime('deleted_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
