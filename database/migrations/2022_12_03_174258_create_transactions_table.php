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
            $table->uuid('id')->primary();
            $table->uuid('transfer_id');
            $table->string('transaction_code')->unique();
            $table->decimal('amount', 19, 2);
            $table->dateTime('transaction_date');
            $table->mediumText('motive');
            $table->uuid('transfer_type_id');
            $table->timestamps();

            $table->foreign('transfer_id')->references('id')->on('transfers');
            $table->foreign('transfer_type_id')->references('id')->on('transfer_types');
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
