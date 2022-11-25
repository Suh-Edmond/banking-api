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
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('account_number');
            $table->enum('status', ['ACTIVE', 'INACTIVE']);
            $table->double('current_balance', 10, 2)->default(10000.00);
            $table->double('available_balance', 10, 2)->default(10000.00);
            $table->enum('account_type', ['CURRENT', 'SAVING']);
            $table->string('telephone')->unique();
            $table->string('bank_name');
            $table->string('bank_code');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
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
