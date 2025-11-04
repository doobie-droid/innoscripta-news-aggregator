<?php

namespace App\Services\Contracts;

use App\DTOs\ArticleDTO;

interface NewsApiInterface
{

    /**
     * @param array<string, mixed> $params
     * @return array<ArticleDTO>
     */
    public function fetchTrendingArticles(): array;

    /**
     * @param array<string, mixed> $params
     * @return array<ArticleDTO>
     */
    public function fetchArticles(array $params = []): array;

    public function getSourceIdentifier(): string;
}
