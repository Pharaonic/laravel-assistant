<?php

namespace Pharaonic\Laravel\Assistant\Http\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * Filtering trait for Eloquent models.
 * 
 * @method static \Illuminate\Database\Eloquent\Builder filter(\Illuminate\Database\Eloquent\Builder $query, \App\Helpers\Classes\BaseFilter $filter = null)
 */
trait Filterable
{
    /**
     * Apply all relevant thread filters.
     *
     * @param Builder $query
     * @param BaseFilter $filter
     * @return Builder
     */
    public function scopeFilter(Builder $query, BaseFilter $filter = null)
    {
        if (!$filter) {
            if (!class_exists($filterName = $this->getFilterClassName())) {
                throw new \Exception('Filter class not found: ' . $filterName);
            }

            $filter = app()->make($filterName);
        }

        return $filter->apply($query);
    }

    /**
     * Get the filter class name.
     *
     * @return string|null
     */
    private function getFilterClassName()
    {
        return $this->filter ?? str_replace('Models', 'Http\\Filters', get_class($this)) . 'Filter';
    }
}
