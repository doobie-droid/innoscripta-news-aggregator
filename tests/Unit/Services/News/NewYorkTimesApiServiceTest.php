<?php

namespace Tests\Unit\Clients;

use App\DTOs\ArticleDTO;
use App\Services\News\NewsApiService;
use App\Services\News\NewYorkTimesApiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NewYorkTimesApiServiceTest extends TestCase
{
    public function test_it_fetches_trending_articles_for_new_york_times_api_and_returns_dtos()
    {

        Http::fake([
            'https://api.nytimes.com/*' => Http::response([
                "status" => "OK",
                "copyright" => "Copyright (c) 2025 The New York Times Company. All Rights Reserved.",
                "response" => [
                    "docs" => [
                        [
                            "abstract" => "The late vice president had called President Trump 'a coward' and a 'threat to our republic' and endorsed former Vice President Kamala Harris in 2024.",
                            "byline" => [
                                "original" => "By Minho Kim"
                            ],
                            "document_type" => "article",
                            "headline" => [
                                "main" => "Some Republicans Honor Dick Cheney, While Trump Remains Silent",
                                "kicker" => "",
                                "print_headline" => ""
                            ],
                            "_id" => "nyt://article/14a1e14c-d24d-5ba4-889d-5362fc3894a0",
                            "keywords" => [
                                // ... keywords array
                            ],
                            "multimedia" => [
                                "caption" => "Flags at the White House were lowered to half-staff in honor of former Vice President Dick Cheney on Tuesday.",
                                "credit" => "Doug Mills/The New York Times",
                                "default" => [
                                    "url" => "https://static01.nyt.com/images/2025/11/04/multimedia/04dc-cheney-reaction-mlfq/04dc-cheney-reaction-mlfq-articleLarge.jpg",
                                    "height" => 400,
                                    "width" => 600
                                ],
                                "thumbnail" => [
                                    "url" => "https://static01.nyt.com/images/2025/11/04/multimedia/04dc-cheney-reaction-mlfq/04dc-cheney-reaction-mlfq-thumbStandard.jpg",
                                    "height" => 75,
                                    "width" => 75
                                ]
                            ],
                            "news_desk" => "Washington",
                            "pub_date" => "2025-11-04T21:23:27Z",
                            "section_name" => "U.S.",
                            "subsection_name" => "Politics",
                            "web_url" => "https://www.nytimes.com/2025/11/04/us/politics/republicans-honor-dick-cheney.html",
                        ],
                        [
                            "abstract" => "While some national parks are seeing damage and illegal activity during the government shutdown, Doug Burgum is traveling around the Middle East, selling American gas and oil.",
                            "byline" => [
                                "original" => "By Maxine Joselow"
                            ],
                            "document_type" => "article",
                            "headline" => [
                                "main" => "Interior Secretary Faces Scrutiny for Travel Amid Shutdown",
                                "kicker" => "",
                                "print_headline" => ""
                            ],
                            "_id" => "nyt://article/3f56251e-ce42-5201-b103-e699ba9908b5",
                            "keywords" => [
                                // ... keywords array
                            ],
                            "multimedia" => [
                                "caption" => "Interior secretary Doug Burgum addressing the annual Abu Dhabi International Petroleum Exhibition and Conference in the United Arab Emirates on Monday.",
                                "credit" => "Amr Alfiky/Reuters",
                                "default" => [
                                    "url" => "https://static01.nyt.com/images/2025/11/04/multimedia/04cli-burgum-travel-fzwt/04cli-burgum-travel-fzwt-articleLarge.jpg",
                                    "height" => 400,
                                    "width" => 600
                                ],
                                "thumbnail" => [
                                    "url" => "https://static01.nyt.com/images/2025/11/04/multimedia/04cli-burgum-travel-fzwt/04cli-burgum-travel-fzwt-thumbStandard.jpg",
                                    "height" => 75,
                                    "width" => 75
                                ]
                            ],
                            "news_desk" => "Climate",
                            "pub_date" => "2025-11-04T21:21:27Z",
                            "section_name" => "Climate",
                            "subsection_name" => "",
                            "web_url" => "https://www.nytimes.com/2025/11/04/climate/interior-secretary-travel-shutdown.html",
                        ],
                        [
                            "abstract" => "",
                            "byline" => [
                                "original" => "By Nicholas Fandos"
                            ],
                            "document_type" => "article",
                            "headline" => [
                                "main" => "Mamdani plans to keep Tisch as police commissioner if elected.",
                                "kicker" => "",
                                "print_headline" => ""
                            ],
                            "_id" => "nyt://article/74218376-1a20-5a36-a8dc-1880da0b1796",
                            "keywords" => [],
                            "multimedia" => [
                                "caption" => "",
                                "credit" => "",
                                "default" => [
                                    "url" => "",
                                    "height" => 0,
                                    "width" => 0
                                ],
                                "thumbnail" => [
                                    "url" => "",
                                    "height" => 0,
                                    "width" => 0
                                ]
                            ],
                            "news_desk" => "Metro",
                            "pub_date" => "2025-11-04T21:15:01Z",
                            "section_name" => "New York",
                            "subsection_name" => "",
                            "web_url" => "https://www.nytimes.com/live/2025/11/04/nyregion/nyc-mayor-election/mamdani-jessica-tisch-nypd",
                        ],
                    ],
                    "metadata" => [
                        "hits" => 10000,
                        "offset" => 0,
                        "time" => 257
                    ]
                ]
            ], 200),
        ]);

        $service = new NewYorkTimesApiService();

        $articles = $service->fetchTrendingArticles();

        $this->assertCount(3, $articles);
        $this->assertContainsOnlyInstancesOf(ArticleDTO::class, $articles);

        $this->assertEquals(
            'Some Republicans Honor Dick Cheney, While Trump Remains Silent',
            $articles[0]->title
        );
        $this->assertEquals(
            "The late vice president had called President Trump 'a coward' and a 'threat to our republic' and endorsed former Vice President Kamala Harris in 2024.",
            $articles[0]->description
        );
        $this->assertEquals('https://www.nytimes.com/2025/11/04/us/politics/republicans-honor-dick-cheney.html', $articles[0]->url);
        $this->assertEquals('2025-11-04T21:23:27Z', $articles[0]->publishedAt);
        $this->assertEquals('new-york-times', $articles[0]->source);
    }
}
