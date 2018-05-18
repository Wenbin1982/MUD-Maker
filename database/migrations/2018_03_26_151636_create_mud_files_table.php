<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMudFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mud_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('last_update_user_id');
            $table->string('delete_name_user')->nullable();
            $table->string('domain');
            $table->string('manufacturer');
            $table->string('model');
            $table->string('deviceType');
            $table->string('software');
            $table->string('deviceSelect');
            $table->string('path');
            $table->string('nameMudFile');
            $table->string('link_mud_file')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('mud_files');
    }
}
