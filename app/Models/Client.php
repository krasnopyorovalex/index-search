<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\IpAddress;
use App\Domain\Manticore\ValueObjects\Id;
use App\Domain\Manticore\ValueObjects\Table;
use App\Jobs\Manticore\CreateTableJob;
use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property HasMany<PageUrl> $pageUrls
 * @property string $host
 * @property string $name
 * @property string $ip
 * @property string $email
 * @property bool $is_active
 */
final class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
        'ip' => IpAddress::class
    ];

    /** @return HasMany<PageUrl, $this> */
    public function pageUrls(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', 1);
    }

    protected static function booted(): void
    {
        self::created(fn(self $client) => dispatch(new CreateTableJob(new Table(new Id($client->id)))));
    }
}
