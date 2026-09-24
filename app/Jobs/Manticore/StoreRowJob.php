<?php

declare(strict_types=1);

namespace App\Jobs\Manticore;

use App\Domain\Manticore\ValueObjects\Id;
use App\Domain\Manticore\ValueObjects\Row;
use App\Domain\Manticore\ValueObjects\Table;
use App\Models\Page;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class StoreRowJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private(set) readonly Table $table,
        private(set) readonly Id $modelId
    ) {}

    public function handle(): void
    {
        try {
            $page = Page::query()->where('id', $this->modelId)->with('pageContent')->firstOrFail();

            DB::connection('manticore')
                ->table($this->table->getName())
                ->insert(
                    $this->table->addRow(Row::fromPageContent($page->pageContent))->toArray()
                );
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['exception' => $th]);
        }
    }
}
