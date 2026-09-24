<?php

declare(strict_types=1);

namespace App\Domain\Crawler\DataTransferObjects;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;

final class ClientData
{
    public ?string $url;

    private(set) int $id;
    private(set) string $site;
    private(set) string $name;
    private(set) string $email;
    private(set) string $ip;
    private(set) bool $isActive;

    /** @param Client $model */
    private function __construct(Model $model)
    {
        $this->id = $model->id;
        $this->site = $model->site;
        $this->name = $model->name;
        $this->email = $model->email;
        $this->ip = $model->ip;
        $this->isActive = $model->is_active;
    }

    /** @param Client $model */
    public static function fromEloquent(Model $model): ClientData
    {
        return new self($model);
    }
}
