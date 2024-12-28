<?php

namespace Pharaonic\Laravel\Assistant\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @method Builder apply(Builder $builder)
 * @method mixed default($value, array $list, $default = null)
 * @method array getFilters()
 */
abstract class BaseFilter
{
    /**
     * The request instance.
     * 
     * @var Request
     */
    protected $request;

    /**
     * The Eloquent builder.
     *
     * @var Builder
     */
    protected $builder;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Create a new BaseFilters instance.
     *
     * @param Request $request
     */
    final public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply the filters.
     *
     * @param  Builder $builder
     * @return Builder
     */
    final public function apply(Builder $builder)
    {
        $this->builder = $builder;

        if (method_exists($this, 'boot')) {
            call_user_func([$this, 'boot'], $this->request);
        }

        foreach ($this->filters as $filter) {
            $value = $this->request->query($filter);

            if ($this->request->has($filter)) {
                $methodName = Str::camel($filter);

                if (!isset($value)) {
                    return $this->builder;
                }
            } else {
                $methodName = 'default' . Str::studly($filter);
            }

            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            }
        }

        return $this->builder;
    }

    /**
     * check if value is in list and return it, otherwise return default
     *
     * @param  mixed $value
     * @param  array $list
     * @param  mixed $default
     * @return mixed|null
     */
    final public function default($value, array $list, $default = null)
    {
        if (in_array($value, $list)) {
            return $value;
        }

        return $default;
    }

    /**
     * Get the filters names.
     *
     * @return array
     */
    final public function getFilters()
    {
        return $this->filters;
    }
}
