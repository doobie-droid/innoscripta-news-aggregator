<?php


namespace App\Http\Controllers\API;

use App\Filters\ArticleFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleController extends Controller
{
    /**
     * List Articles
     * Returns a paginated list of articles filtered by certain criteria
     * 
     * @queryParam sort_by string Field to sort by. Available: published_at, title, source. Default: published_at Example: published_at
     * @queryParam latest boolean If true, returns only articles from the last 24 hours. Example: true
     * @queryParam from date Start date for articles (Y-m-d format). Example: 2024-01-01
     * @queryParam to date End date for articles (Y-m-d format). Example: 2024-12-31
     * @queryParam category integer Filter by specific category ID. Example: 1
     * @queryParam categories string Comma-separated list of category IDs to include. Example: 1,2,3
     * @queryParam source string Filter by specific news source. Example: the-guardian
     * @queryParam sources string Comma-separated list of sources to include. Example: newsapi,the-guardian,nytimes
     * @queryParam authors string Comma-separated list of authors to filter by. Example: john-doe,jane-smith
     * @queryParam search string Search term to match in title and content. Example: artificial intelligence
     * @queryParam page integer Page number for pagination. Example: 1
     * @queryParam per_page integer Number of items per page. Example: 20
     * 
     * @apiResourceCollection App\Http\Resources\ArticleResource
     * @apiResourceModel      App\Models\Article
     */
    public function index(ArticleFilter $filters): AnonymousResourceCollection
    {
        $articles = Article::filter($filters)
            ->paginate(
                request()->get('per_page', 15)
            );
        return ArticleResource::collection($articles);
    }
}
