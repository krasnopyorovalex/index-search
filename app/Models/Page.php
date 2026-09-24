<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\StrBinary;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property string $body_bin
 * @property PageContent $pageContent
 * @property PageUrl $pageUrl
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    protected $casts = [
        'body_bin' => StrBinary::class,
    ];

    protected $with = ['client'];

    /** @return BelongsTo<Client, $this> */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /** @return HasOne<PageContent, $this> */
    public function pageContent(): HasOne
    {
        return $this->hasOne(PageContent::class, 'page_id');
    }

    /** @return HasOne<PageUrl, $this> */
    public function pageUrl(): HasOne
    {
        return $this->hasOne(PageUrl::class, 'page_id');
    }

    protected static function booted(): void
    {
//        self::deleted(fn(self $page) => dispatch(new DeleteRowsJob(
//            new Table(new ClientId($page->client_id))->addRow(Row::fromModel($page->load('pageContent')))
//        )));
    }
}
