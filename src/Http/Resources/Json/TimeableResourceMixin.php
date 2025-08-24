<?php

namespace Pharaonic\Laravel\Assistant\Http\Resources\Json;

use Illuminate\Contracts\Auth\Authenticatable;

class TimeableResourceMixin
{
    public function datetime()
    {
        return function (
            string $attribute,
            ?string $format = null,
            ?string $action = null,
            ?Authenticatable $user = null,
            $isHijri = false
        ) {
            return datetime(
                $this->{'resource'},
                $attribute,
                $format,
                $action,
                $user,
                $isHijri
            );
        };
    }
}
