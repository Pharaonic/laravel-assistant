<?php

namespace Pharaonic\Laravel\Assistant\Http\Resources\Json;

use Illuminate\Contracts\Auth\Authenticatable;

class TimeableResourceMixin
{
    /**
     * Get the datetime in the user's timezone with the specified format and action.
     *
     * @param string $attribute
     * @param string|null $format
     * @param string|null $action
     * @param Authenticatable|null $user
     * @return array|null
     */
    public function datetime(string $attribute, ?string $format, ?string $action, ?Authenticatable $user, $isHijri = false)
    {
        return datetime($this->resource, $attribute, $format, $action, $user, $isHijri);
    }
}
