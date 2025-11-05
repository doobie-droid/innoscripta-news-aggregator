<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\Category;
use App\Services\Contracts\NewsApiInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class FetchTrendingArticles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private NewsApiInterface $service
    ) {}
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $articles = $this->service->fetchTrendingArticles();


        foreach ($articles as $articleDTO) {
            $article = Article::firstOrCreate(
                ['url' => $articleDTO->url],
                [
                    'title' => $articleDTO->title,
                    'description' => $articleDTO->description,
                    'cover_image' => $articleDTO->coverImage,
                    'content' => $articleDTO->content,
                    'author' => $articleDTO->author,
                    'published_at' => $articleDTO->publishedAt,
                    'source' => $articleDTO->source,
                ]
            );

            if (!empty($articleDTO->categories)) {
                $categoryIds = collect($articleDTO->categories)
                    ->map(function ($category) {
                        return Category::firstOrCreate(
                            ['slug' => Str::slug($category)],
                            [
                                'name' => $category,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        )->id;
                    })
                    ->toArray();

                $article->categories()->sync($categoryIds);
            }
        }
    }
}
