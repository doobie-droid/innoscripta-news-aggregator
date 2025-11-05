<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * List Categories
     * Returns a non-paginated list of all categories
     *
     * @apiResourceCollection App\Http\Resources\CategoryResource
     * @apiResourceModel      App\Models\Category
     */
    public function index()
    {
        $categories = Category::orderBy('name')->get();

        return CategoryResource::collection($categories)
            ->additional([
                'meta' => [
                    'total_categories' => $categories->count()
                ]
            ]);
    }
}
