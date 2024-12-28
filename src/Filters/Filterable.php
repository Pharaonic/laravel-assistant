<?php

namespace Pharaonic\Laravel\Assistant\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Scope a query to apply filters.
     *
     * @param Builder $query
     * @param BaseFilter $filter
     * @return Builder
     * 
     * @throws \Exception
     */
    public function scopeFilter(Builder $query, BaseFilter $filter = null)
    {
        if (!$filter) {
            if (!isset($this->filter) || !$this->filter) {
                throw new \Exception('Filter property is not defined in ' . get_class($this) . ' model.');
            }

            $filter = app()->make($this->filter);
        }

        return $filter->apply($query);
    }
}
