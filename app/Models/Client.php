<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Manticore\ValueObjects\Id;
use App\Domain\Manticore\ValueObjects\Table;
use App\Jobs\Manticore\CreateTableJob;
use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property HasMany<Page> $pages
 */
final class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** @return HasMany<Page, $this> */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    protected static function booted(): void
    {
        self::created(fn(self $client) => dispatch(new CreateTableJob(new Table(new Id($client->id)))));
    }
}
