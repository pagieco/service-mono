<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_events', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('profile_id');
            $table->string('event_type');
            $table->json('data')->nullable();
            $table->timestamps();

            $table->foreign('profile_id')
                ->references('id')->on('profiles')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_events');
    }
}
