<?php

declare(strict_types=1);

namespace App\Jobs\Crawler;

use App\Domain\Crawler\Contracts\UrlRepository;
use App\Domain\Crawler\DataTransferObjects\ClientData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class StoreUrlJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private(set) readonly ClientData $clientData)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(UrlRepository $repository): void
    {
        $repository->store($this->clientData);
    }
}
