<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->text('url');
            $table->string('format');
            $table->string('quality');
            $table->string('path');
            $table->string('status')->default('not_processed');
            $table->string('job_uuid')->nullable();
            $table->string('filename')->nullable();
            $table->text('command')->nullable();
            $table->text('extra_flags')->nullable();
            $table->string('extractor')->nullable();
            $table->float('processing_duration', 8, 2)->nullable();
            $table->longText('output')->nullable();
            $table->text('title')->nullable();
            $table->integer('duration')->nullable();
            $table->bigInteger('size')->nullable();
            $table->string('audio_info')->nullable();
            $table->string('video_info')->nullable();
            $table->text('download_url')->nullable();
            $table->text('error')->nullable();
            $table->foreignId('playlist_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
