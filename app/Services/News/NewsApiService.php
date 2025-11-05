<?php

namespace App\Services\News;

use App\DTOs\ArticleDTO;
use App\Enums\NewsClient;
use App\Services\API;
use App\Services\Contracts\NewsApiInterface;
use Exception;

class NewsApiService extends API implements NewsApiInterface
{
    protected ?string $secret;

    public function __construct()
    {
        $this->secret = config('services.news_api.key');
    }

    public function baseUrl(): string
    {
        return 'https://newsapi.org';
    }
    public function getSourceIdentifier(): string
    {
        return NewsClient::NEWS_API->value;
    }

    /**
     * Fetch trending articles
     * 
     * @return array<ArticleDTO>
     */
    public function fetchTrendingArticles(): array
    {

        $response = $this->_get('v2/top-headlines', ['language' => 'en']);

        if (!$response['status'] || $response['status'] != 'ok') {
            throw new Exception('Failed to fetch trending articles from News API.');
        }

        $articles = $response['articles'];

        return array_map(fn($item) => $this->toDTO($item), $articles);
    }

    /**
     * Fetch articles with optional filters.
     *
     * @param array{
     *     q?: string,
     *     from?: string,
     *     to?: string,
     *     language?: string,
     *     page?: int,
     *     sources?: string
     * } $params
     *
     * @return array<ArticleDTO>
     */
    public function fetchArticles(array $params = []): array
    {
        $response = $this->_get('v2/everything', $params);

        if (!$response['status'] || $response['status'] != 'ok') {
            throw new Exception('Failed to fetch articles from News API.');
        }

        $articles = $response['articles'];

        return array_map(fn($item) => $this->toDTO($item), $articles);
    }

    protected function toDTO(array $item): ArticleDTO
    {
        return new ArticleDTO(
            title: $item['title'] ?? '',
            description: $item['description'] ?? '',
            url: $item['url'] ?? '',
            coverImage: $item['urlToImage'] ?? '',
            content: $item['content'] ?? '',
            author: $item['author'] ?? '',
            publishedAt: $item['publishedAt'] ?? null,
            source: $item['source']['id'] ?? 'news-api',
            categories: [],
        );
    }
}
