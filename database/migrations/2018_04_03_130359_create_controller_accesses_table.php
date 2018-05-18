<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateControllerAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controller_accesses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('portl')->nullable();
            $table->string('port')->nullable();
            $table->string('initiatedBy');
            $table->string('protocolSelect');
            $table->integer('json_id');
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
        Schema::dropIfExists('controller_accesses');
    }
}
