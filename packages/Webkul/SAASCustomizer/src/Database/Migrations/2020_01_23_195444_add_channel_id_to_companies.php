<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChannelIdToCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // SAASCustomizer package migration alterations
        Schema::table('companies', function (Blueprint $table) {
            $table->integer('channel_id')->unsigned();
        });

        // Core package migration alterations
        Schema::table('channels', function (Blueprint $table) {
            $table->unique(['hostname'], 'channels_hostname_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('channel_id');
        });

        Schema::table('channels', function (Blueprint $table) {
            $table->dropUnique('channels_hostname_unique');
        });
    }
}
