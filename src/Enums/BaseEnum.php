<?php

namespace Pharaonic\Laravel\Assistant\Enums;

use Illuminate\Support\Str;

trait BaseEnum
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

        return constant('static::' . $caseName)?->value == $this->value;
    }

    /**
     * Get the enum cases count.
     *
     * @return int
     */
    public static function count()
    {
        return count(static::cases());
    }

    /**
     * Get the enum list
     *
     * @return array
     * @throws \Exception
     */
    public static function list(): array
    {
        if (defined('static::LABELS')) {
            return __(constant('static::LABELS'));
        }

        $list = [];

        foreach (static::cases() as $case) {
            $list[$case->value] = $case->name;
        }

        return $list;
    }

    /**
     * Get the enum string
     *
     * @return string|null
     * @throws \Exception
     */
    public function toString()
    {
        return static::list()[$this->value] ?? null;
    }
}
