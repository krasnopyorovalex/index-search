<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\StrBinary;
use App\Domain\Manticore\ValueObjects\Id;
use App\Domain\Manticore\ValueObjects\Row;
use App\Domain\Manticore\ValueObjects\Table;
use App\Jobs\Manticore\DeleteRowsJob;
use Database\Factories\PageUrlFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property Client $client
 * @property Page $page
 */
final class PageUrl extends Model
{
    /** @use HasFactory<PageUrlFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'url_bin' => StrBinary::class,
    ];

    protected $fillable = [
        'url',
        'client_id',
    ];

    protected $with = ['client'];

    /** @return BelongsTo<Client, $this> */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /** @return HasOne<Page, $this> */
    public function page(): HasOne
    {
        return $this->hasOne(Page::class, 'page_url_id', 'page_url_id');
    }

    protected static function booted(): void
    {
        self::deleted(static fn(self $pageUrl) => dispatch(new DeleteRowsJob(
            new Table(new Id($pageUrl->client_id))->addRow(Row::fromPageContent($pageUrl->page->pageContent))
        )));
    }
}
