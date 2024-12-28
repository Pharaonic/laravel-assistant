<?php

namespace Pharaonic\Laravel\Assistant\Paginator;

use Illuminate\Pagination\LengthAwarePaginator;
use Pharaonic\Laravel\Assistant\Filters\BaseFilter;

trait Pagination
{
    /**
     * Prepare the response of the given paginator.
     *
     * @param LengthAwarePaginator $paginator
     * @param string|null $resource
     * @param array $appends
     * @return array
     */
    public function paginator(LengthAwarePaginator $paginator, string $resource = null, array|string $appends = [])
    {
        // Append to the paginator query string
        if (is_subclass_of($appends, BaseFilter::class)) {
            if (!is_object($appends)) {
                $appends = new $appends(request());
            }

            $appends = $appends->getFilters();
        }

        foreach ($appends as $key => $value) {
            if (is_int($key)) {
                $key = $value;
                $value = request()->get($key);
            }

            $paginator->appends($key, $value);
        }

        $paginator->appends('per_page', $paginator->perPage());

        return [
            'paginator' => [
                'links' => $paginator->linkCollection(),
                'meta' => [
                    'pages' => [
                        'current' => $paginator->currentPage(),
                        'total' => $paginator->lastPage(),
                    ],
                    'items' => [
                        'per_page' => $paginator->perPage(),
                        'total' => $paginator->total(),
                        'displayed' => $paginator->count(),
                    ]
                ]
            ],
            'items' => $resource ? $resource::collection($paginator->items()) : $paginator->items()
        ];
    }
}
