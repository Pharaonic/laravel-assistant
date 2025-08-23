<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('datetime')) {
    /**
     * Get the datetime in the user's timezone with the specified format and action.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $attribute
     * @param string|null $format
     * @param string|null $action
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @return array|null
     */
    function datetime(
        Illuminate\Database\Eloquent\Model $model,
        string $attribute,
        ?string $format = null,
        ?string $action = null,
        ?\Illuminate\Contracts\Auth\Authenticatable $user = null,
        $isHijri = false
    ) {
        if (!$model->{$attribute}) {
            return null;
        }

        $format ??= 'LL - hh:mm A';
        $action ??= 'isoFormat';

        $dt = $model->{$attribute}->timezone(($user ?? Auth::user())?->timezone ?? config('app.timezone'));

        if ($isHijri && class_exists('Pharaonic\Hijri\Hijri')) {
            $hijri = $dt->toHijri();
            $hijriData = [
                'datetime' => $hijri,
                'formatted' => $hijri->{$action}($format),
            ];
        }

        return [
            'datetime' => $dt,
            'formatted' => $dt->{$action}($format),
            'difference' => $dt->diffForHumans(),
            'hijri' => $hijriData ?? null,
        ];
    }
}
