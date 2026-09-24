<?php

namespace App\Console\Commands\Manticore;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Signature('manticore:create-table {table}')]
#[Description('Создание таблицы в Manticore Search')]
class CreateTable extends Command
{
    public function handle(): int
    {
        $table = $this->argument('table');

        DB::connection('manticore')->statement(
            "CREATE TABLE IF NOT EXISTS $table (
                id bigint,
                title text,
                content text,
                created_at timestamp,
                updated_at timestamp,
                vector FLOAT_VECTOR KNN_TYPE='hnsw' KNN_DIMS='384' HNSW_SIMILARITY='COSINE' MODEL_NAME='sentence-transformers/all-MiniLM-L6-v2' FROM='content'
            )
            min_stemming_len = '3'
            morphology='lemmatize_ru_all, metaphone'
            charset_table='0..9, A..Z->a..z, _, a..z, U+410..U+42F->U+430..U+44F, U+430..U+44F, U+401->U+451, U+451'
            body_strip='1'
            min_infix_len='3'
            index_exact_words='1'
            body_remove_elements='style, script'
            body_index_attrs='img=alt,title; a=title'
            "
        );

        Log::info(sprintf('Table `%s` was creates successfully.', $table));

        return 0;
    }
}
