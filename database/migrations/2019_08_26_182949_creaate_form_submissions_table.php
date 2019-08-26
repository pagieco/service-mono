<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaateFormSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('team_id');
            $table->uuid('domain_id');
            $table->uuid('form_id');
            $table->json('submission_data');
            $table->timestamps();

            $table->foreign('team_id')
                ->references('id')->on('teams')
                ->onDelete('cascade');

            $table->foreign('domain_id')
                ->references('id')->on('domains')
                ->onDelete('cascade');

            $table->foreign('form_id')
                ->references('id')->on('forms')
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
        Schema::dropIfExists('form_submissions');
    }
}
