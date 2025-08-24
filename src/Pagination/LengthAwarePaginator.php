<?php

namespace Pharaonic\Laravel\Assistant\Pagination;

use Illuminate\Pagination\LengthAwarePaginator as LaravelLengthAwarePaginator;
use Pharaonic\Laravel\Assistant\Http\Filters\BaseFilter;

class LengthAwarePaginator extends LaravelLengthAwarePaginator
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(?string $resource = null, array|string $appends = [], array $extra = [])
    {
        // Prepare the BaseFilter appends
        if (is_subclass_of($appends, BaseFilter::class)) {
            if (!is_object($appends)) {
                $appends = new $appends(request());
            }

            $appends = $appends->getFilters();
        }

        // Append filters
        foreach ($appends as $key => $value) {
            if (is_int($key)) {
                $key = $value;
                $value = request()->get($key);
            }

            $this->appends($key, $value);
        }

        $this->appends('per_page', $this->perPage());

        return array_merge(
            [
                'paginator' => [
                    'links' => $this->generatePaginationLinks($this),

                    'meta' => [
                        'pages' => [
                            'current' => $this->currentPage(),
                            'total' => $this->lastPage(),
                        ],
                        'items' => [
                            'per_page' => $this->perPage(),
                            'total' => $this->total(),
                            'displayed' => $this->count(),
                        ]
                    ]
                ],
                'items' => $resource ? $resource::collection($this->items()) : $this->items()
            ],
            $extra
        );
    }

    /**
     * Generate the pagination links.
     *
     * @param  LengthAwarePaginator $paginator
     * @return array
     */
    protected function generatePaginationLinks(LengthAwarePaginator $paginator)
    {
        $links = [
            'first' => [
                'enabled' => false,
                'page' => null,
                'label' => function_exists('__') ? __('pagination.first') : 'First',
                'url' => $paginator->url(1),
            ],
            'previous' => [
                'enabled' => false,
                'page' => null,
                'label' => function_exists('__') ? __('pagination.previous') : 'Previous',
                'url' => $paginator->previousPageUrl(),
            ],
            'next' => [
                'enabled' => false,
                'page' => null,
                'label' => function_exists('__') ? __('pagination.next') : 'Next',
                'url' => $paginator->nextPageUrl(),
            ],
            'last' => [
                'enabled' => false,
                'page' => null,
                'label' => function_exists('__') ? __('pagination.last') : 'Last',
                'url' => $paginator->url($paginator->lastPage()),
            ],
        ];

        // First
        if (!$paginator->onFirstPage()) {
            $links['previous']['page'] = 1;
            $links['first']['enabled'] = true;
        }

        // Previous
        if ($paginator->currentPage() > 1) {
            $links['previous']['page'] = $paginator->currentPage() - 1;
            $links['previous']['enabled'] = true;
        }

        // Next
        if ($paginator->hasMorePages()) {
            $links['next']['page'] = $paginator->currentPage() + 1;
            $links['next']['enabled'] = true;
        }

        // Last
        if ($paginator->currentPage() < $paginator->lastPage()) {
            $links['last']['page'] = $paginator->lastPage();
            $links['last']['enabled'] = true;
        }

        return $links;
    }
}
