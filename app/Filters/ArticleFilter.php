<?php

namespace App\Filters;

use App\Filters\Filters;
use Carbon\Carbon;

class ArticleFilter extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [
        'sort_by',
        'latest',
        'from',
        'to',
        'category',
        'source',
        'sources',
        'categories',
        'authors',
        'search'
    ];

    protected function sort_by($value)
    {
        return $this->builder->orderBy($value, request('order_by', 'asc'));
    }

    protected function latest()
    {
        request()->merge(['per_page' => 1]);
        return $this->builder->latest();
    }

    protected function from($value)
    {
        return $this->builder->where('published_at', '>=', Carbon::parse($value)->startOfDay());
    }

    protected function to($value)
    {
        return $this->builder->where('published_at', '<=', Carbon::parse($value)->endOfDay());
    }
    protected function source($value)
    {
        return $this->builder->where('source', $value);
    }

    protected function sources($value)
    {
        $sources = explode(',', $value);
        return $this->builder->whereIn('source', $sources);
    }

    protected function category($value)
    {
        return $this->builder->where('category', $value);
    }

    protected function categories($value)
    {
        $categories = explode(',', $value);
        return $this->builder->whereIn('category', $categories);
    }

    protected function authors($value)
    {
        $authors = explode(',', $value);
        return $this->builder->whereIn('author', $authors);
    }

    protected function search($value)
    {
        return $this->builder->where(function ($query) use ($value) {
            $query->where('title', 'LIKE', "%{$value}%")
                ->orWhere('description', 'LIKE', "%{$value}%")
                ->orWhere('content', 'LIKE', "%{$value}%");
        });
    }
}
