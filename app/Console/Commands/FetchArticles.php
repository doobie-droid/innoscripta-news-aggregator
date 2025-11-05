<?php

namespace App\Console\Commands;

use App\Jobs\FetchTrendingArticles;
use App\Services\News\GuardianApiService;
use App\Services\News\NewsApiService;
use App\Services\News\NewYorkTimesApiService;
use Illuminate\Console\Command;

class FetchArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is useful for fetching articles from the aggregated sources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        FetchTrendingArticles::dispatch(new NewsApiService());
        FetchTrendingArticles::dispatch(new GuardianApiService());
        FetchTrendingArticles::dispatch(new NewYorkTimesApiService());
    }
}
