<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_id')->nullable()->default(null);
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('set null');
            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('type_transaction_id')->nullable()->default(null);
            $table->foreign('type_transaction_id')->references('id')->on('type_transactions')->onDelete('set null');
            $table->float('value')->nullable()->default(null);
            $table->float('balance')->nullable()->default(null);
            $table->string('document')->nullable()->default(null);
            $table->string('number_card')->nullable()->default(null);
            $table->string('number_phone')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);
            $table->datetime('date')->nullable()->default(null);
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
        Schema::dropIfExists('transactions');
    }
}
