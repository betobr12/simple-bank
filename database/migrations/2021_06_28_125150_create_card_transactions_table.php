<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_id')->nullable()->default(null);
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('set null');
            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('card_id')->nullable()->default(null);
            $table->foreign('card_id')->references('id')->on('cards')->onDelete('set null');
            $table->float('value')->nullable()->default(null);
            $table->float('balance')->nullable()->default(null);
            $table->string('store')->nullable()->default(null);
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
        Schema::dropIfExists('card_transactions');
    }
}
