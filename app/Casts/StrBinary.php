<?php

declare(strict_types=1);

namespace App\Casts;

use App\Domain\Crawler\ValueObjects\StrBinary as StrBinaryValueObject;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class StrBinary implements CastsAttributes
{
    /**
     * Чтение из БД: 16 байт бинарных данных → 32-символьная hex-строка.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_null($value)) {
            return null;
        }

        return (string) new StrBinaryValueObject($value)->toStrFromBinary();
    }

    /**
     * Запись в БД: «сырой» контент → MD5 → 16 байт бинарных данных.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_null($value)) {
            return null;
        }

        return (string) new StrBinaryValueObject($value)->asMd5()->asBinary();
    }
}
