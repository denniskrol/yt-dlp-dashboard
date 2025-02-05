<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->text('url');
            $table->string('format');
            $table->string('quality');
            $table->string('path');
            $table->boolean('auto_update');
            $table->boolean('prefix_playlist_name')->default(false);
            $table->string('status')->default('not_processed');
            $table->text('extra_flags')->nullable();
            $table->string('extractor')->nullable();
            $table->float('processing_duration', 8, 2)->nullable();
            $table->longText('output')->nullable();
            $table->text('title')->nullable();
            $table->integer('playlist_items_count')->default(0);
            $table->text('error')->nullable();
            $table->dateTime('checked_at')->nullable();
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
        Schema::dropIfExists('playlists');
    }
}
