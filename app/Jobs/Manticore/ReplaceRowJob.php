<?php

declare(strict_types=1);

namespace App\Jobs\Manticore;

use App\Domain\Manticore\ValueObjects\Table;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class ReplaceRowJob implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Table $table)
    {
    }

    public function handle(): void
    {
        try {
            $row = $this->table->getFirstRow();

            $table = $this->table->getName();

            $sql = <<<SQL
                REPLACE INTO `$table` (`id`, `title`, `description`, `content`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?)
            SQL;

            DB::connection('manticore')->statement($sql, [
                $row->data->id,
                $row->data->title,
                $row->data->description,
                $row->data->content,
                $row->data->createdAt,
                $row->data->updatedAt,
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['exception' => $th]);
        }
    }
}
