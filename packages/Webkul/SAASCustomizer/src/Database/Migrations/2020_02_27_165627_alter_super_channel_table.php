<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSuperChannelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Super Channel package migration alterations
        Schema::table('super_channel', function (Blueprint $table) {
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_description');
            $table->dropColumn('meta_keywords');
            $table->dropColumn('use_seo');
            $table->dropColumn('misc');
            
            $table->string('code')->unique();
            $table->renameColumn('title', 'name');
            $table->renameColumn('domain', 'hostname');
            $table->string('theme')->nullable();
            $table->text('home_page_content')->nullable();
            $table->text('footer_page_content')->nullable();
            $table->json('home_seo')->nullable();

            $table->integer('default_locale_id')->unsigned()->nullable();
            $table->integer('base_currency_id')->unsigned()->nullable();

            $table->foreign('default_locale_id')->references('id')->on('super_locales')->onDelete('cascade');
            $table->foreign('base_currency_id')->references('id')->on('super_currencies')->onDelete('cascade');
        });

        Schema::create('super_channel_locales', function (Blueprint $table) {
            $table->integer('super_channel_id')->unsigned()->nullable();
            $table->integer('locale_id')->unsigned()->nullable();

            $table->primary(['super_channel_id', 'locale_id']);
            
            $table->foreign('super_channel_id')->references('id')->on('super_channel')->onDelete('cascade');
            $table->foreign('locale_id')->references('id')->on('super_locales')->onDelete('cascade');
        });

        Schema::create('super_channel_currencies', function (Blueprint $table) {
            $table->integer('super_channel_id')->unsigned()->nullable();
            $table->integer('currency_id')->unsigned()->nullable();

            $table->primary(['super_channel_id', 'currency_id']);
            $table->foreign('super_channel_id')->references('id')->on('super_channel')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('super_currencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('super_channel_locales');
        Schema::dropIfExists('super_channel_currencies');
    }
}
