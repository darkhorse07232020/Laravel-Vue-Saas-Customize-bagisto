<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperCurrenciesAndExchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('super_currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('symbol')->nullable();
            $table->timestamps();
        });

        Schema::create('super_currency_exchange_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('rate', 24, 12);
            $table->integer('target_currency')->unique()->unsigned();
            $table->foreign('target_currency')->references('id')->on('super_currencies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('super_currencies');
        Schema::dropIfExists('super_currency_exchange_rates');
    }
}
