<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMentorRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mentor_requests', function (Blueprint $table) {
            $table->increments('req_id');
            $table->integer('mentor');
            $table->integer('mentee');
            $table->enum('status',array(0,1,2,3));
            $table->enum('friends',array(0,1));
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
        Schema::dropIfExists('mentor_requests');
    }
}
