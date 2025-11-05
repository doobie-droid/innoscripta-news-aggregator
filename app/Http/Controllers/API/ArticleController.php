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
