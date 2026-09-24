<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_contents', function (Blueprint $table) {
            $table->unsignedBigInteger('page_id')->primary();
            $table->string('title');
            $table->mediumText('body_gzipped')->charset('binary');

            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });

        DB::statement('ALTER TABLE `page_contents` ROW_FORMAT=COMPRESSED KEY_BLOCK_SIZE=8');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_contents');
    }
};
