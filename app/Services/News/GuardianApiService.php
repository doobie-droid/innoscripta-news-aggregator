<?php

namespace App\Services\News;

use App\DTOs\ArticleDTO;
use App\Enums\NewsClient;
use App\Services\API;
use App\Services\Contracts\NewsApiInterface;
use Exception;

class GuardianApiService extends API implements NewsApiInterface
{
    protected ?string $secret;

    public function __construct()
    {
        $this->secret = config('services.guardian.key');
    }

    public function baseUrl(): string
    {
        return 'https://content.guardianapis.com';
    }
    public function getSourceIdentifier(): string
    {
        return NewsClient::GUARDIAN->value;
    }

    /**
     * Fetch trending articles
     * 
     * @return array<ArticleDTO>
     */
    public function fetchTrendingArticles(): array
    {

        $response = $this->_get('search', ['api-key' => $this->secret, 'page-size' => 50]);

        $response = $response['response'];

        if (!$response['status'] || $response['status'] != 'ok') {
            throw new Exception('Failed to fetch trending articles from Guardian Api.');
        }

        $articles = $response['results'];


        return array_map(fn($item) => $this->toDTO($item), $articles);
    }

    /**
     * Fetch articles with optional filters.
     *
     * @param array{
     *     q?: string,
     *     page?: int,
     *     from-date?: string,
     *     to-date?: string,
     *     tag?: string,
     *     page?: int,
     *     page-size?: int,
     * } $params
     *
     * @return array<ArticleDTO>
     */
    public function fetchArticles(array $params = []): array
    {
        $params = array_merge($params, ['api-key' => $this->secret]);;
        $response = $this->_get('search', $params);

        $response = $response['response'];

        if (!$response['status'] || $response['status'] != 'ok') {
            throw new Exception('Failed to fetch articles from Guardian API.');
        }

        $articles = $response['results'];

        return array_map(fn($item) => $this->toDTO($item), $articles);
    }

    protected function toDTO(array $item): ArticleDTO
    {
        return new ArticleDTO(
            title: $item['webTitle'] ?? '',
            description: '',
            url: $item['webUrl'] ?? '',
            coverImage: '',
            content: '',
            author: '',
            publishedAt: $item['webPublicationDate'] ?? null,
            source: NewsClient::GUARDIAN->value,
            categories: [$item['sectionId']]
        );
    }
}
