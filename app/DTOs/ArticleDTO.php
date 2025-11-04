<?php

namespace App\DTOs;

class ArticleDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public string $url,
        public string $coverImage,
        public string $content,
        public string $author,
        public ?string $publishedAt,
        public string $source,
    ) {}
}
