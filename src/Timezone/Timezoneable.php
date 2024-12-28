<?php

namespace Pharaonic\Laravel\Assistant\Timezone;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

/**
 * @method string|\Carbon\Carbon timezone(string $attribute, string|null $format = 'Y-m-d H:i', string $action = 'format', Authenticatable|null $user = null)
 */
trait Timezoneable
{
    /**
     * Get the datetime in the user's timezone.
     *
     * @param string $attribute
     * @param string|null $format
     * @param string $action
     * @param Authenticatable|null $user
     * @return string|\Carbon\Carbon
     */
    public function timezone(string $attribute, string|null $format = 'Y-m-d H:i', string $action = 'format', Authenticatable $user = null)
    {
        $dt = $this->{$attribute}->timezone(($user ?? Auth::user())?->timezone ?? 'UTC');

        if ($format === null) {
            return $dt;
        }

        return $dt->{$action}($format);
    }
}
