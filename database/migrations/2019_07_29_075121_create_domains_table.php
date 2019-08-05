<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('team_id');
            $table->uuid('environment_id')->nullable();
            $table->string('domain_name');
            $table->timestamps();

            $table->foreign('team_id')
                ->references('id')->on('teams')
                ->onDelete('cascade');

            $table->foreign('environment_id')
                ->references('id')->on('environments')
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
        Schema::dropIfExists('domains');
    }
}
