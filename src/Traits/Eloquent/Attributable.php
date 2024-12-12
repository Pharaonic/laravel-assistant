<?php

namespace MoamenEltouny\Laravel\Assistant\Traits\Eloquent;

use MoamenEltouny\Laravel\Assistant\Classes\Eloquent\AttributableItem;

/**
 * @method AddAttributable(string $name, $value = null, callable $accessor = null, callable $mutator = null)
 * @method getAttribute(string $name)
 * @method setAttribute(string $name, $value)
 */
trait Attributable
{
    /**
     * The custom attributes that should be added to the model.
     *
     * @var array
     */
    protected $attributables = [];

    /**
     * Add a new attributable to the model.
     *
     * @param string $name
     * @param mixed $value
     * @param callable|null $accessor
     * @param callable|null $mutator
     * @return static
     */
    public function addAttributable(string $name, $value = null, callable $accessor = null, callable $mutator = null)
    {
        $this->attributables[$name] = new AttributableItem(
            name: $name,
            value: $value,
            accessor: $accessor,
            mutator: $mutator
        );

        return $this;
    }

    /**
     * Get an attribute from the model.
     *
     * @param string $key
     * @return mixeed
     */
    public function getAttribute($name)
    {
        if (array_key_exists($name, $this->attributables)) {
            return $this->attributables[$name]->get();
        }

        return parent::getAttribute($name);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param string $key
     * @param  $value
     * @return mixed
     */
    public function setAttribute($name, $value)
    {
        if (array_key_exists($name, $this->attributables)) {
            return $this->attributables[$name]->set($value);
        }

        return parent::setAttribute($name, $value);
    }

    /**
     * Clear all attributables.
     *
     * @return void
     */
    public function clearAttributables()
    {
        $this->attributables = [];
    }
}
