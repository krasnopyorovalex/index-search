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

    protected $with = ['pageUrl'];

    protected $casts = [
        'body_bin' => StrBinary::class,
    ];

    /** @return HasOne<PageContent, $this> */
    public function pageContent(): HasOne
    {
        return $this->hasOne(PageContent::class);
    }

    /** @return BelongsTo<PageUrl, $this> */
    public function pageUrl(): BelongsTo
    {
        return $this->belongsTo(PageUrl::class, 'page_url_id', 'page_url_id');
    }
}
