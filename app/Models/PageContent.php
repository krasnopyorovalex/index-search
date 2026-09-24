<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\StrGzip;
use App\Domain\Manticore\ValueObjects\Id;
use App\Domain\Manticore\ValueObjects\Row;
use App\Domain\Manticore\ValueObjects\Table;
use App\Jobs\Manticore\ReplaceRowJob;
use App\Jobs\Manticore\StoreRowsJob;
use Database\Factories\PageContentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $title
 * @property string $description
 * @property string $body_gzipped
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
        return $this->belongsTo(Page::class, 'page_id', 'id')->without('client');
    }

    protected static function booted(): void
    {
        self::created(function (PageContent $pageContent) {
            dispatch(new StoreRowsJob(
                new Table(new Id($pageContent->page->client_id)),
                new Id($pageContent->page_id)
            ));
        });

//        self::updated(function (Page $page) {
//            $table = new Table(new ClientId($page->client_id))->addRow(Row::fromModel($page->load('pageContent')));
//            ReplaceRowJob::dispatchIf((bool)$page->wasChanged(['title']), $table);
//        });
    }
}
