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
//        /** @see https://sqlfordevs.com/sorted-table-faster-range-scan */
//        DB::statement(<<<'SQL'
//                CREATE TABLE `page_urls` (
//                  `client_id` bigint unsigned NOT NULL,
//                  `page_id` bigint unsigned NOT NULL,
//                  `url` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
//                  `url_bin` binary(16) GENERATED ALWAYS AS (unhex(md5(`url`))) STORED,
//                  PRIMARY KEY (`client_id`,`page_id`),
//                  KEY `page_urls_url_bin_index` (`url_bin`),
//                  CONSTRAINT `page_urls_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
//                  CONSTRAINT `page_urls_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
//                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
//            SQL);

        Schema::create('page_urls', function (Blueprint $table) {
            $table->unsignedBigInteger('page_id')->primary();
            $table->string('url', 512);

            $table->char('url_bin', 16)
                ->charset('binary')
                ->virtualAs(new \Illuminate\Database\Query\Expression('UNHEX(MD5(`url`))'))
                ->unique();

            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_urls');
    }
};
