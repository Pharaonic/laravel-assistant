<?php

namespace Pharaonic\Laravel\Assistant\Http\Requests;

class TranslatableFormRequestMixin
{
    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('locale')) {
            $locale = $this->input('locale') ?? [];

            foreach ($locale as $key => $values) {
                if (empty(array_filter($values))) {
                    unset($locale[$key]);
                }
            }

            $this->merge(compact('locale'));
        }

        // Handle the payload before validation
        if (method_exists($this, 'before')) {
            $this->before();
        }
    }
}
