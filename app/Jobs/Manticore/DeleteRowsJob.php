<?php

declare(strict_types=1);

namespace App\Jobs\Manticore;

use App\Domain\Manticore\ValueObjects\Table;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class DeleteRowsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private(set) readonly Table $table)
    {
        //
    }

    public function handle(): void
    {
        try {
            $ids = array_map(fn($row) => $row['id'], $this->table->toArray());

            DB::connection('manticore')
                ->table($this->table->getName())
                ->whereIn('id', $ids)
                ->delete();
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['exception' => $th]);
        }
    }
}
