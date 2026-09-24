<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PageUrlFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class PageUrl extends Model
{
    /** @use HasFactory<PageUrlFactory> */
    use HasFactory;

    public $timestamps = false;

    /** @return BelongsTo<Page, $this> */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id')->without('client');
    }
}
