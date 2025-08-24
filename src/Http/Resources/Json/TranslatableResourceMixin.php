<?php

namespace Pharaonic\Laravel\Assistant\Http\Resources\Json;

class TranslatableResourceMixin
{
    public function translations()
    {
        return function (string ...$fields) {
            if (!$this->{'resource'}->relationLoaded('translations') || $this->{'resource'}->translations->isEmpty()) {
                return null;
            }

            $translations = [];

            foreach (locale()->supported as $locale) {
                foreach ($fields as $field) {
                    $translations[$locale][$field] = $this->{'resource'}->translate($locale)?->{$field};
                }
            }

            return $translations;
        };
    }

    public function translation()
    {
        return function (string ...$fields) {
            if (!$this->{'resource'}->relationLoaded('translations') || $this->{'resource'}->translations->isEmpty()) {
                return null;
            }

            $translation = $this->{'resource'}->translate(locale()->current);

            if (!$translation) {
                return null;
            }

            $result = [];

            foreach ($fields as $field) {
                $result[$field] = $translation->{$field};
            }

            return $result;
        };
    }
}
