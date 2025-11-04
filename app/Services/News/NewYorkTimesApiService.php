<?php

namespace App\Services\News;

use App\DTOs\ArticleDTO;
use App\Enums\NewsClient;
use App\Services\API;
use App\Services\Contracts\NewsApiInterface;
use Exception;

class NewYorkTimesApiService extends API implements NewsApiInterface
{
    protected ?string $secret;

    public function __construct()
    {
        $this->secret = config('services.new_york_times.key');
    }

    public function baseUrl(): string
    {
        return 'https://api.nytimes.com';
    }
    public function getSourceIdentifier(): string
    {
        return NewsClient::NEW_YORK_TIMES->value;
    }

    /**
     * Fetch trending articles
     * 
     * @return array<ArticleDTO>
     */
    public function fetchTrendingArticles(): array
    {
        $response = $this->_get('svc/search/v2/articlesearch.json', ['api-key' => $this->secret]);

        if (!$response['status'] || strtolower($response['status']) != 'ok') {
            throw new Exception('Failed to fetch trending articles from New York Times Api.');
        }

        $articles = $response['response']['docs'];

        return array_map(fn($item) => $this->toDTO($item), $articles);
    }

    /**
     * Fetch articles with optional filters.
     *
     * @param array{
     *     q?: string,
     * } $params
     *
     * @return array<ArticleDTO>
     */
    public function fetchArticles(array $params = []): array
    {
        $params = array_merge($params, ['api-key' => $this->secret]);
        $response = $this->_get('svc/search/v2/articlesearch.json', $params);

        if (!$response['status'] || strtolower($response['status']) != 'ok') {
            throw new Exception('Failed to fetch articles from New York Times Api.');
        }

        $articles = $response['response']['docs'];

        return array_map(fn($item) => $this->toDTO($item), $articles);
    }

    protected function toDTO(array $item): ArticleDTO
    {
        return new ArticleDTO(
            title: $item['headline']['main'] ?? '',
            description: $item['abstract'] ?? '',
            url: $item['web_url'] ?? '',
            coverImage: isset($item['default']['url']) ? $item['default']['url'] : $item['multimedia']['thumbnail']['url'],
            content: $item['snippet'] ?? '',
            author: str_replace('By ', '', $item['byline']['original'] ?? ''),
            publishedAt: $item['pub_date'] ?? null,
            source: NewsClient::NEW_YORK_TIMES->value,
        );
    }
}
