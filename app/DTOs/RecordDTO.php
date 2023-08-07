<?php

namespace App\DTOs;

class RecordDTO
{
    public ?string $title;
    public ?int $release_year;
    public ?string $imdb_id;
    public ?string $images;
    public ?string $metadata;

    public function __construct(array $data)
    {
        $this->title = $data['title'] ?? null;
        $this->release_year = $data['release_year'] ?? null;
        $this->imdb_id = $data['imdb_id'] ?? null;
        $this->images = $data['images'] ?? null;

        $this->metadata = $data['metadata'] ?? null;
    }
}
