<?php

namespace Pharaonic\Laravel\Assistant\Resources;

/**
 * @method string|null file(string $name)
 * @method array files()
 */
trait Fileable
{
    /**
     * Get the file url.
     *
     * @return string|null
     */
    public function file(string $name)
    {
        return $this->{$name}?->url;
    }

    /**
     * Get the files urls.
     *
     * @return array
     */
    public function files()
    {
        return [
            'files' => $this->files->isEmpty()
                ? null :
                $this->files
                ->keyBy('field')
                ->map(fn($file) => $file->url)
        ];
    }
}
