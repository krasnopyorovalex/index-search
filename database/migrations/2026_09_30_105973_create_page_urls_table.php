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
        /** @see https://sqlfordevs.com/sorted-table-faster-range-scan */
        DB::statement(<<<'SQL'
                CREATE TABLE `page_urls` (
                  `client_id` bigint unsigned NOT NULL,
                  `page_url_id` bigint unsigned NOT NULL AUTO_INCREMENT UNIQUE,
                  `url` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
                  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(),
                  `url_bin` binary(16) GENERATED ALWAYS AS (unhex(md5(`url`))) STORED NOT NULL,
                  PRIMARY KEY (`client_id`,`page_url_id`),
                  UNIQUE `page_urls_url_bin_index` (`url_bin`),
                  CONSTRAINT `page_urls_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_urls');
    }
};
