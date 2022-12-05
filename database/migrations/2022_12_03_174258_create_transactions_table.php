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
            $table->string('transaction_code')->unique();
            $table->decimal('amount_deposited', 19, 2);
            $table->dateTime('transaction_date');
            $table->mediumText('motive');
            $table->uuid('transfer_type_id');
            $table->string('account_number_from');
            $table->string('account_number_to');
            $table->enum('status', ['COMPLETED', 'PENDING', 'DECLINED', 'FAILED']);
            $table->timestamps();

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
