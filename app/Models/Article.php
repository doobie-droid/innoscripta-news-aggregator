<?php

namespace App\Models;

use App\Filters\ArticleFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Apply all relevant thread filters.
     *
     * @param                 $query
     * @param ArticleFilter $filters
     *
     * @return Builder
     */
    public function scopeFilter($query, ArticleFilter $filters): Builder
    {
        return $filters->apply($query);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'article_category');
    }
}
