<?php

namespace Pharaonic\Laravel\Assistant\Enums;

use Illuminate\Support\Str;

trait Enumerable
{
    /**
     * Check the enum status
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (str_starts_with($name, 'is')) {
            return $this->checkCase(substr($name, 2));
        }

        if (method_exists($this, $name)) {
            return $this->{$name}(...$arguments);
        }

        return;
    }

    /**
     * Check the enum value
     *
     * @param string $name
     * @return boolean
     */
    private function checkCase(string $name)
    {
        $caseName = Str::upper(Str::snake($name));

        return constant('self::' . $caseName)?->value == $this->value;
    }

    /**
     * Check if the enum value is equal to the given value.
     *
     * @param int|self|null $value
     * @return bool
     */
    public function eq(int|self $value = null)
    {
        return $this->value == ($value instanceof self ? $value->value : $value);
    }

    /**
     * Get the enum cases count.
     *
     * @return int
     */
    public static function count()
    {
        return count(self::cases());
    }

    /**
     * Get the enum label
     *
     * @return string|null
     */
    public function label()
    {
        if (defined('self::LABELS')) {
            $labels = constant('self::LABELS');
        } else {
            $namespace = $this::class;

            if (!str_contains($namespace, 'App\\Modules')) {
                $labels = 'enum.' . $namespace;
            } else {
                $labels = Str::slug(
                    strtolower(
                        trim(
                            preg_replace(
                                '/[A-Z-]/',
                                ' $0',
                                Str::before(
                                    Str::after($namespace, 'App\\Modules\\'),
                                    '\\'
                                )
                            )
                        )
                    )
                ) . '::enum.' . $namespace;
            }
        }

        $key = $labels . '.' . $this->value;

        return app()->has('translator') ? __($key) : $key;
    }

    /**
     * Get the enum list.
     *
     * @return array
     */
    public static function list()
    {
        return collect(static::cases())
            ->map(fn ($case) => $case->info())
            ->values()
            ->toArray();
    }


    /**
     * Get the enum information.
     *
     * @return array
     */
    public function info()
    {
        return [
            'label' => $this->label(),
            'name' => $this->name,
            'value' => $this->value,
        ];
    }
}
