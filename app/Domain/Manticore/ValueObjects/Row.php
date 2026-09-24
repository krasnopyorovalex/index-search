<?php

declare(strict_types=1);

namespace App\Domain\Manticore\ValueObjects;

use App\Domain\Manticore\DataTransferObjects\RowData;
use App\Models\Page;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements Arrayable<int, mixed>
 */
final readonly class Row implements Arrayable
{
    private function __construct(private(set) RowData $data)
    {
    }

    /**
     * @param array<string, int|string> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            new RowData(
                id: (int)$data['id'],
                title: (string)$data['title'],
                content: (string)$data['content'],
                createdAt: (int)$data['created_at'],
                updatedAt: (int)$data['updated_at'],
            )
        );
    }

    /**
     * @param Page $model
     */
    public static function fromModel(Model $model): self
    {
        return new self(
            new RowData(
                id: (int)$model->getKey(),
                title: $model->pageContent->title,
                content: $model->pageContent->body_gzipped,
                createdAt: $model->created_at->getTimestamp(),
                updatedAt: $model->updated_at->getTimestamp()
            )
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->data->id,
            'title' => $this->data->title,
            'content' => $this->data->content,
            'created_at' => $this->data->createdAt,
            'updated_at' => $this->data->updatedAt,
        ];
    }
}
