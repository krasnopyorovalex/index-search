<?php

declare(strict_types=1);

namespace App\Jobs\Manticore;

use App\Console\Commands\Manticore\CreateTable;
use App\Domain\Manticore\ValueObjects\Table;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

final class CreateTableJob implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Table $table)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Artisan::call(CreateTable::class, ['table' => $this->table->getName()]);
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage(), ['exception' => $exception]);
        }
    }
}
