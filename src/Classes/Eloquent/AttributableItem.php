<?php

namespace Pharaonic\Laravel\Assistant\Classes\Eloquent;

class AttributableItem
{
    /**
     * The attribute name.
     *
     * @var mixed
     */
    protected $name;

    /**
     * The attribute original value.
     *
     * @var mixed
     */
    public $original;

    /**
     * The attribute value.
     *
     * @var mixed
     */
    protected $value;

    /**
     * The accessor method.
     *
     * @var callable
     */
    protected $accessor;

    /**
     * The mutator method.
     *
     * @var callable
     */
    protected $mutator;

    /**
     * The attribute is dirty.
     *
     * @var boolean
     */
    protected $isDirty = false;

    /**
     * Create a new AttributableItem instance.
     *
     * @param string $name
     * @param mixed $value
     * @param callable|null $accessor
     * @param callable|null $mutator
     */
    public function __construct(
        string $name,
        $value = null,
        callable $accessor = null,
        callable $mutator = null
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->accessor = $accessor;
        $this->mutator = $mutator;
    }

    /**
     * Get an attribute from the model.
     *
     * @return mixed
     */
    public function get()
    {
        if ($this->accessor) {
            return call_user_func_array(
                $this->accessor,
                [$this->value]
            );
        }
        return $this->value;
    }

    /**
     * Set a given attribute on the model.
     *
     * @param mixed $value
     * @return mixed
     */
    public function set($value)
    {
        if ($this->mutator) {
            $res = call_user_func_array(
                $this->mutator,
                [
                    $value,
                    $this->value,
                    $this->name
                ]
            );
        } else {
            $res = $value;
        }

        if ($res !== $this->value) {
            $this->isDirty = true;
            $this->original = $this->value;
        }

        return $this->value = $value;
    }

    /**
     * Check if the attribute is dirty.
     *
     * @return boolean
     */
    public function isDirty()
    {
        return $this->isDirty;
    }

    /**
     * Get the attribute name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the attribute value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the original value.
     *
     * @return mixed
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * Force the attribute value.
     *
     * @param mixed $value
     * @return void
     */
    public function forceValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Force the original value.
     *
     * @param mixed $value
     * @return void
     */
    public function forceOriginal($value)
    {
        $this->original = $value;

        return $this;
    }

    /**
     * Force the attribute value.
     *
     * @param mixed $value
     * @return static
     */
    public function reset($value)
    {
        $this->value = $value;
        $this->original = null;
        $this->isDirty = false;

        return $this;
    }
}
