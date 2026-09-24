<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class StrGzip implements CastsAttributes
{
    /**
     * @param  array<string, false|string>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): false|string
    {
        return gzdecode($value);
    }

    /**
     * @param  array<string, false|string>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): false|string
    {
        return gzencode($value, 9);
    }
}
