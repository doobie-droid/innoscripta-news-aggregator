<?php

namespace Tests\Unit\Services\News;

use App\DTOs\ArticleDTO;
use App\Services\News\NewsApiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NewsApiServiceTest extends TestCase
{
    public function test_it_fetches_trending_articles_and_returns_dtos(): void
    {

        Http::fake([
            'https://newsapi.org/v2/top-headlines*' => Http::response([
                'status' => 'ok',
                'totalResults' => 35,
                'articles' => [
                    [
                        'source' => [
                            'id' => 'cnn',
                            'name' => 'CNN',
                        ],
                        'author' => 'Kyle Feldscher',
                        'title' => 'A wild finish in Cincy, a star player’s gruesome injury and multiple games come down to the final seconds in NFL’s Week 9 - CNN',
                        'description' => 'After Week 8 of the NFL season was marked by blowouts, NFL fans were due some excitement in Week 9.',
                        'url' => 'https://www.cnn.com/2025/11/03/sport/nfl-wrap-week-9',
                        'urlToImage' => 'https://media.cnn.com/api/v1/images/stellar/prod/07-gettyimages-2244690006.JPG?c=16x9&q=w_800,c_fill',
                        'publishedAt' => '2025-11-03T14:05:51Z',
                        'content' => "After Week 8 of the NFL season was marked by blowouts, NFL fans were due some excitement in Week 9.\r\nBoy, did they get it.\r\nMultiple games went down to the wire, including one finish in Cincinnati th… [+5463 chars]",
                    ],
                    [
                        'source' => [
                            'id' => null,
                            'name' => 'Aboutamazon.com',
                        ],
                        'author' => 'Amazon Staff',
                        'title' => 'AWS and OpenAI announce multi-year strategic partnership - About Amazon',
                        'description' => 'Partnership will enable OpenAI to run its advanced AI workloads on AWS’s world-class infrastructure starting immediately.',
                        'url' => 'https://www.aboutamazon.com/news/aws/aws-open-ai-workloads-compute-infrastructure',
                        'urlToImage' => 'https://assets.aboutamazon.com/dims4/default/1704cd2/2147483647/strip/true/crop/1999x1000+0+42/resize/1200x600!/quality/90/?url=https%3A%2F%2Famazon-blogs-brightspot.s3.amazonaws.com%2Fa3%2Fce%2F7e1631f748beb430293b8a60d80c%2Foai-aws-hero.png',
                        'publishedAt' => '2025-11-03T14:01:35Z',
                        'content' => "Partnership will enable OpenAI to run its advanced AI workloads on AWS’s world-class infrastructure starting immediately.",
                    ],
                ],
            ], 200),
        ]);

        $service = new NewsApiService();

        $articles = $service->fetchTrendingArticles();

        $this->assertCount(2, $articles);
        $this->assertContainsOnlyInstancesOf(ArticleDTO::class, $articles);

        $this->assertEquals(
            'A wild finish in Cincy, a star player’s gruesome injury and multiple games come down to the final seconds in NFL’s Week 9 - CNN',
            $articles[0]->title
        );
        $this->assertEquals(
            'After Week 8 of the NFL season was marked by blowouts, NFL fans were due some excitement in Week 9.',
            $articles[0]->description
        );
        $this->assertEquals('https://www.cnn.com/2025/11/03/sport/nfl-wrap-week-9', $articles[0]->url);
        $this->assertEquals('2025-11-03T14:05:51Z', $articles[0]->publishedAt);
        $this->assertEquals('cnn', $articles[0]->source);
    }

    public function test_it_fetches_articles_and_returns_dtos(): void
    {
        Http::fake([
            'https://newsapi.org/v2/everything*' => Http::response([
                'status' => 'ok',
                'totalResults' => 2,
                'articles' => [
                    [
                        'source' => [
                            'id' => 'bbc-news',
                            'name' => 'BBC News',
                        ],
                        'author' => 'John Doe',
                        'title' => 'New discovery in space',
                        'description' => 'Scientists have discovered a new planet.',
                        'url' => 'https://www.bbc.com/news/science/space',
                        'urlToImage' => 'https://example.com/image.jpg',
                        'publishedAt' => '2025-11-04T12:00:00Z',
                        'content' => 'Detailed article content here...',
                    ],
                    [
                        'source' => [
                            'id' => 'cnn',
                            'name' => 'CNN',
                        ],
                        'author' => 'Jane Smith',
                        'title' => 'Global economy shows signs of recovery',
                        'description' => 'The global economy is bouncing back.',
                        'url' => 'https://www.cnn.com/economy/recovery',
                        'urlToImage' => 'https://example.com/economy.jpg',
                        'publishedAt' => '2025-11-04T10:00:00Z',
                        'content' => 'Economic analysis content...',
                    ],
                ],
            ], 200),
        ]);

        $service = new NewsApiService();

        $articles = $service->fetchArticles([
            'q' => 'science',
            'language' => 'en',
            'page' => 1,
        ]);

        $this->assertCount(2, $articles);
        $this->assertContainsOnlyInstancesOf(ArticleDTO::class, $articles);

        $this->assertEquals('New discovery in space', $articles[0]->title);
        $this->assertEquals('Scientists have discovered a new planet.', $articles[0]->description);
        $this->assertEquals('https://www.bbc.com/news/science/space', $articles[0]->url);
        $this->assertEquals('2025-11-04T12:00:00Z', $articles[0]->publishedAt);
        $this->assertEquals('bbc-news', $articles[0]->source);
    }
}
