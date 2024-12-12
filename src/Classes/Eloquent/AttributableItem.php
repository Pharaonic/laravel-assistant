<?php

namespace Pharaonic\Laravel\Assistant\Classes\Eloquent;

class AttributableItem
{
    /**
     * The attribute name.
     *
     * @var mixed
     */
    public $name;

    /**
     * The attribute value.
     *
     * @var mixed
     */
    public $value;

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
     * Create a new AttributableItem instance.
     *
     * @param string $name
     * @param mixed $value
     * @param callable|null $accessor
     * @param callable|null $mutator
     */
    public function __construct(string $name, $value = null, callable $accessor = null, callable $mutator = null)
    {
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
            return $this->value = call_user_func_array(
                $this->mutator,
                [
                    $value,
                    $this->value,
                    $this->name
                ]
            );
        }

        return $this->value = $value;
    }
}
