<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('team_id');
            $table->uuid('domain_id');
            $table->string('hash');
            $table->string('filename');
            $table->string('original_filename');
            $table->text('description')->nullable();
            $table->string('extension');
            $table->string('mimetype');
            $table->integer('filesize');
            $table->json('extra_attributes')->nullable();
            $table->string('path');
            $table->string('thumb_path')->nullable();
            $table->timestamps();

            $table->foreign('team_id')
                ->references('id')->on('teams')
                ->onDelete('cascade');

            $table->foreign('domain_id')
                ->references('id')->on('domains')
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
        Schema::dropIfExists('assts');
    }
}
