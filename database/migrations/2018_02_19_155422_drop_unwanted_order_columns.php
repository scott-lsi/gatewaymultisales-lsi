<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUnwantedOrderColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('add1');
            $table->dropColumn('add2');
            $table->dropColumn('add3');
            $table->dropColumn('add4');
            $table->dropColumn('postcode');
            $table->dropColumn('country');
            $table->dropColumn('countrycode');
            $table->dropColumn('deliverydate');
            $table->dropColumn('recipient');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
