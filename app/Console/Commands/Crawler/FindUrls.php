<?php

declare(strict_types=1);

namespace App\Console\Commands\Crawler;

use App\Domain\Crawler\Contracts\UrlFinder;
use App\Domain\Crawler\DataTransferObjects\ClientData;
use App\Domain\Crawler\Services\RedisUrlSeeder;
use App\Domain\Crawler\ValueObjects\StrBinary;
use App\Models\Client;
use App\Models\PageUrl;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Command\Command as CommandConsole;

#[Signature('crawler:find-urls {client_id}')]
#[Description('Команда загрузки всех url сайта клиента в БД')]
class FindUrls extends Command
{
    public function __construct(
        private readonly UrlFinder $finder,
        private readonly RedisUrlSeeder $redisUrlSeeder
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $clientId = $this->argument('client_id');

        try {
            $clientData = ClientData::fromEloquent(Client::query()->where('id', $clientId)->firstOrFail());

            $urls = PageUrl::query()->select(['url_bin', 'client_id'])->where('client_id', $clientData->id)->get()->pluck(
                'url_bin'
            )->map(fn($url) => (string)new StrBinary($url)->asBinary())->toArray();

            $this->redisUrlSeeder->seed($clientData, $urls);

            $this->finder->find($clientData);
        } catch (ModelNotFoundException $e) {
            Log::error(sprintf('Not found data for Client Id %s', $clientId), [
                'client_id' => $clientId,
                'exception' => $e,
                'message' => $e->getMessage(),
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['exception' => $th]);
        }

        return CommandConsole::SUCCESS;
    }
}
