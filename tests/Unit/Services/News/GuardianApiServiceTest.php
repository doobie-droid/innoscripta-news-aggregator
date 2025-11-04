<?php

namespace Tests\Unit\Clients;

use App\DTOs\ArticleDTO;
use App\Services\News\GuardianApiService;
use App\Services\News\NewsApiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GuardianApiServiceTest  extends TestCase
{
    public function test_it_fetches_trending_articles_from_the_guardian_and_returns_dtos(): void
    {
        Http::fake([
            'https://content.guardianapis.com/*' => Http::response([
                "response" => [
                    "status" => "ok",
                    "userTier" => "developer",
                    "total" => 2623019,
                    "startIndex" => 1,
                    "pageSize" => 5,
                    "currentPage" => 1,
                    "pages" => 524604,
                    "orderBy" => "newest",
                    "results" => [
                        [
                            "id" => "society/2025/nov/04/mps-ask-hmrc-explain-child-benefit-error-froze-payments-parents",
                            "type" => "article",
                            "sectionId" => "society",
                            "sectionName" => "Society",
                            "webPublicationDate" => "2025-11-04T22:34:51Z",
                            "webTitle" => "MPs ask HMRC to explain child benefit error that froze payments to parents",
                            "webUrl" => "https://www.theguardian.com/society/2025/nov/04/mps-ask-hmrc-explain-child-benefit-error-froze-payments-parents",
                            "apiUrl" => "https://content.guardianapis.com/society/2025/nov/04/mps-ask-hmrc-explain-child-benefit-error-froze-payments-parents",
                            "isHosted" => false,
                            "pillarId" => "pillar/news",
                            "pillarName" => "News"
                        ],
                        [
                            "id" => "education/2025/nov/04/national-curriculum-review-in-england-10-key-recommendations",
                            "type" => "article",
                            "sectionId" => "education",
                            "sectionName" => "Education",
                            "webPublicationDate" => "2025-11-04T22:30:08Z",
                            "webTitle" => "National curriculum review in England: 10 key recommendations",
                            "webUrl" => "https://www.theguardian.com/education/2025/nov/04/national-curriculum-review-in-england-10-key-recommendations",
                            "apiUrl" => "https://content.guardianapis.com/education/2025/nov/04/national-curriculum-review-in-england-10-key-recommendations",
                            "isHosted" => false,
                            "pillarId" => "pillar/news",
                            "pillarName" => "News"
                        ],
                        [
                            "id" => "education/2025/nov/04/england-curriculum-should-focus-less-on-exams-and-more-on-life-skills-finds-review",
                            "type" => "article",
                            "sectionId" => "education",
                            "sectionName" => "Education",
                            "webPublicationDate" => "2025-11-04T22:30:08Z",
                            "webTitle" => "England curriculum should focus less on exams and more on life skills, finds review",
                            "webUrl" => "https://www.theguardian.com/education/2025/nov/04/england-curriculum-should-focus-less-on-exams-and-more-on-life-skills-finds-review",
                            "apiUrl" => "https://content.guardianapis.com/education/2025/nov/04/england-curriculum-should-focus-less-on-exams-and-more-on-life-skills-finds-review",
                            "isHosted" => false,
                            "pillarId" => "pillar/news",
                            "pillarName" => "News"
                        ],
                        [
                            "id" => "football/live/2025/nov/04/liverpool-v-real-madrid-champions-league-live",
                            "type" => "liveblog",
                            "sectionId" => "football",
                            "sectionName" => "Football",
                            "webPublicationDate" => "2025-11-04T22:27:53Z",
                            "webTitle" => "Liverpool 1-0 Real Madrid: Champions League – live reaction",
                            "webUrl" => "https://www.theguardian.com/football/live/2025/nov/04/liverpool-v-real-madrid-champions-league-live",
                            "apiUrl" => "https://content.guardianapis.com/football/live/2025/nov/04/liverpool-v-real-madrid-champions-league-live",
                            "isHosted" => false,
                            "pillarId" => "pillar/sport",
                            "pillarName" => "Sport"
                        ],
                        [
                            "id" => "football/live/2025/nov/04/tottenham-v-copenhagen-psg-v-bayern-munich-and-more-champions-league-live",
                            "type" => "liveblog",
                            "sectionId" => "football",
                            "sectionName" => "Football",
                            "webPublicationDate" => "2025-11-04T22:26:24Z",
                            "webTitle" => "Tottenham 4-0 Copenhagen, PSG 1-2 Bayern Munich, and more: Champions League – as it happened",
                            "webUrl" => "https://www.theguardian.com/football/live/2025/nov/04/tottenham-v-copenhagen-psg-v-bayern-munich-and-more-champions-league-live",
                            "apiUrl" => "https://content.guardianapis.com/football/live/2025/nov/04/tottenham-v-copenhagen-psg-v-bayern-munich-and-more-champions-league-live",
                            "isHosted" => false,
                            "pillarId" => "pillar/sport",
                            "pillarName" => "Sport"
                        ]
                    ]
                ]
            ], 200),
        ]);

        $service = new GuardianApiService();

        $articles = $service->fetchTrendingArticles();

        $this->assertCount(5, $articles);
        $this->assertContainsOnlyInstancesOf(ArticleDTO::class, $articles);

        $this->assertEquals(
            'MPs ask HMRC to explain child benefit error that froze payments to parents',
            $articles[0]->title
        );
        $this->assertEquals(
            '',
            $articles[0]->description
        );
        $this->assertEquals('https://www.theguardian.com/society/2025/nov/04/mps-ask-hmrc-explain-child-benefit-error-froze-payments-parents', $articles[0]->url);
        $this->assertEquals('2025-11-04T22:34:51Z', $articles[0]->publishedAt);
    }
}
