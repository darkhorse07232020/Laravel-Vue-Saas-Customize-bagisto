<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RestructureCompanyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('logo');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('zip_code');
            $table->dropColumn('country');
            $table->dropColumn('address1');
            $table->dropColumn('address2');
        });

        Schema::create('super_channel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('domain')->unique();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('meta_title');
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->boolean('use_seo')->default(0);
            $table->json('misc')->nullable();
            $table->timestamps();
        });

        Schema::create('company_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->text('address1')->nullable();
            $table->text('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->json('misc')->nullable();
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('company_personal_details', function (Blueprint $table) {
            $table->string('phone')->nullable();
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
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->text('address1')->nullable();
            $table->text('address2')->nullable();
        });

        Schema::table('company_personal_details', function (Blueprint $table) {
            $table->dropColumn('phone');
        });

        Schema::dropIfExists('company_addresses');

        Schema::dropIfExists('super_channel');

        Schema::dropIfExists('super_currencies');

        Schema::dropIfExists('super_locales');
    }
}
