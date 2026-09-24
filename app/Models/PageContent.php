<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\StrGzip;
use App\Domain\Manticore\ValueObjects\Id;
use App\Domain\Manticore\ValueObjects\Row;
use App\Domain\Manticore\ValueObjects\Table;
use App\Jobs\Manticore\ReplaceRowJob;
use App\Jobs\Manticore\StoreRowJob;
use Database\Factories\PageContentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $title
 * @property string $body_gzipped
 * @property Page $page
 */
final class PageContent extends Model
{
    public $timestamps = false;

    protected $casts = [
        'body_gzipped' => StrGzip::class,
    ];

    /** @use HasFactory<PageContentFactory> */
    use HasFactory;

    /** @return BelongsTo<Page, $this> */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }

    protected static function booted(): void
    {
        self::created(function (PageContent $pageContent) {
            dispatch(new StoreRowJob(
                new Table(new Id($pageContent->page->pageUrl->client_id)),
                new Id($pageContent->page_id)
            ));
        });

        self::updated(function (PageContent $pageContent) {
            ReplaceRowJob::dispatchIf(
                (bool)$pageContent->wasChanged(['title']),
                new Table(new Id($pageContent->page->pageUrl->client_id))->addRow(Row::fromPageContent($pageContent))
            );
        });
    }
}
