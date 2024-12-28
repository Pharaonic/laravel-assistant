<?php

namespace Pharaonic\Laravel\Assistant\Attributes;

/**
 * @method static addAttributable(string $name, $value = null, callable $accessor = null, callable $mutator = null)
 * @method mixed getAttribute(string $name)
 * @method mixed setAttribute(string $name, $value)
 * @method array getAttributables()
 * @method array getDirtyAttributables()
 * @method void clearAttributables()
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
     * @return mixed
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
     * Get all attributables.
     *
     * @return array
     */
    public function getAttributables()
    {
        return $this->attributables;
    }

    /**
     * Get all dirty attributables.
     *
     * @return void
     */
    public function getDirtyAttributables()
    {
        return array_filter(
            $this->attributables,
            fn($attributable) => $attributable->isDirty()
        );
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
