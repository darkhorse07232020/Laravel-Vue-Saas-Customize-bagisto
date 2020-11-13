<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSuperAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        // Super Channel package migration alterations
        Schema::table('super_admins', function (Blueprint $table) {
            $table->increments('id')->change();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique()->change();
            $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->boolean('status')->default(0)->change();
            $table->integer('role_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
