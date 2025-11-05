<?php


namespace Tests\Feature\Http\Controllers\API;

use App\Models\Article;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        Article::factory()->create([
            'title' => 'Technology Article',
            'source' => 'tech-news',
            'author' => 'John Doe',
            'published_at' => Carbon::now()->subDays(2),
            'created_at' => Carbon::now()->subHour(),
            'updated_at' => Carbon::now()->subHour(),
        ]);

        Article::factory()->create([
            'title' => 'Sports Article',
            'source' => 'sports-news',
            'author' => 'Jane Smith',
            'published_at' => Carbon::now()->subDays(5),
            'created_at' => Carbon::now()->subHour(),
            'updated_at' => Carbon::now()->subHour(),
        ]);

        Article::factory()->create([
            'title' => 'Breaking News Today',
            'source' => 'bbc',
            'author' => 'John Doe',
            'published_at' => Carbon::today(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    public function test_it_returns_paginated_articles()
    {
        $response = $this->getJson(route('api.articles.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description', 'url', 'cover_image', 'content', 'author', 'published_at', 'source']
                ],
                'links',
                'meta'
            ])
            ->assertJsonCount(3, 'data');
    }

    public function test_it_filters_articles_by_source()
    {
        $response = $this->getJson('/api/articles?source=tech-news');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['source' => 'tech-news'])
            ->assertJsonMissing(['source' => 'sports-news']);
    }

    public function test_it_filters_articles_by_multiple_sources()
    {
        $response = $this->getJson('/api/articles?sources=tech-news,bbc');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['source' => 'tech-news'])
            ->assertJsonFragment(['source' => 'bbc'])
            ->assertJsonMissing(['source' => 'sports-news']);
    }

    public function test_it_filters_articles_by_author()
    {
        $response = $this->getJson(route('api.articles.index', [
            'authors' => 'John Doe'
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['author' => 'John Doe'])
            ->assertJsonMissing(['author' => 'Jane Smith']);
    }

    public function test_it_filters_articles_by_category()
    {
        $technologyCategory = Category::factory()->withName('Technology')->create();
        $sportsCategory = Category::factory()->withName('Sports')->create();

        $techArticle = Article::factory()->create(['title' => 'Technology Article']);
        $sportsArticle = Article::factory()->create(['title' => 'Sports Article']);

        $techArticle->categories()->sync([$technologyCategory->id]);
        $sportsArticle->categories()->sync([$sportsCategory->id]);

        $response = $this->getJson(route('api.articles.index', [
            'category' => $technologyCategory->id
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Technology Article'])
            ->assertJsonMissing(['title' => 'Sports Article']);
    }

    public function test_it_filters_articles_by_multiple_categories()
    {
        $techCategory = Category::factory()->withName('Technology')->create();
        $sportsCategory = Category::factory()->withName('Sports')->create();
        $newsCategory = Category::factory()->withName('News')->create();

        $techArticle = Article::factory()->create(['title' => 'Technology Article']);
        $sportsArticle = Article::factory()->create(['title' => 'Sports Article']);
        $newsArticle = Article::factory()->create(['title' => 'News Article']);

        $techArticle->categories()->sync([$techCategory->id]);
        $sportsArticle->categories()->sync([$sportsCategory->id]);
        $newsArticle->categories()->sync([$newsCategory->id]);

        $response = $this->getJson(route('api.articles.index', [
            'categories' => $techCategory->id . ',' . $sportsCategory->id 
        ]));


        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['title' => 'Technology Article'])
            ->assertJsonFragment(['title' => 'Sports Article'])
            ->assertJsonMissing(['title' => 'News Article']);
    }

    public function test_it_filters_articles_by_date_range_using_from_and_to()
    {
        $response = $this->getJson(route('api.articles.index', [
            'from' => Carbon::now()->subDays(3)->format('Y-m-d'),
            'to' => Carbon::now()->subDays(1)->format('Y-m-d')
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Technology Article'])
            ->assertJsonMissing(['title' => 'Breaking News Today'])
            ->assertJsonMissing(['title' => 'Sports Article']);
    }


    public function test_it_filters_articles_using_only_from_date()
    {
        $response = $this->getJson(route('api.articles.index', [
            'from' => Carbon::now()->subDays(1)->format('Y-m-d')
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Breaking News Today'])
            ->assertJsonMissing(['title' => 'Technology Article'])
            ->assertJsonMissing(['title' => 'Sports Article']);
    }

    public function test_it_filters_articles_using_only_to_date()
    {
        $response = $this->getJson(route('api.articles.index', [
            'to' => Carbon::now()->subDays(1)->format('Y-m-d')
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['title' => 'Technology Article'])
            ->assertJsonFragment(['title' => 'Sports Article'])
            ->assertJsonMissing(['title' => 'Breaking News Today']);
    }

    public function test_it_filters_articles_with_from_and_to_same_date()
    {
        $sameDate = Carbon::now()->subDays(2)->format('Y-m-d');

        $response = $this->getJson(route('api.articles.index', [
            'from' => $sameDate,
            'to' => $sameDate
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Technology Article']);
    }

    public function test_it_searches_articles_by_keyword()
    {
        $response = $this->getJson('/api/articles?search=Technology');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Technology Article']);
    }

    public function test_it_sorts_articles_by_field()
    {
        $response = $this->getJson('/api/articles?sort_by=published_at&order_by=desc');

        $response->assertStatus(200);

        $articles = $response->json('data');
        $this->assertEquals('Breaking News Today', $articles[0]['title']);
    }

    public function test_it_returns_latest_article()
    {
        $response = $this->getJson(route('api.articles.index', [
            'latest' => true
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Breaking News Today']);
    }

    public function test_it_accepts_custom_per_page_value()
    {
        $response = $this->getJson(route('api.articles.index', [
            'per_page' => 2
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'meta' => ['per_page']
            ])
            ->assertJsonFragment(['per_page' => 2]);
    }

    public function test_it_returns_empty_when_no_results_match_filters()
    {
        $response = $this->getJson('/api/articles?source=non-existent-source');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }
}
