<?php

namespace Pharaonic\Laravel\Assistant\Files;

use Illuminate\Database\Eloquent\Model;

/**
 * @method string|array|null file(string $attribute, bool $showDetails = false, Model|null $model = null)
 * @method array|null files(Model|null $model = null)
 */
trait Fileable
{
    /**
     * Get the file url/details.
     *
     * @param string $attribute
     * @param bool $showDetails
     * @param Model|null $model
     * @return string|array|null
     */
    public function file(string $attribute, bool $showDetails = false, Model $model = null)
    {
        $model ??= $this;
        $file = $model->{$attribute};

        if (!$file) {
            return null;
        }

        if ($showDetails) {
            return $file->upload->only('name', 'extension', 'mime') + [
                'size' => $file->size(),
                'url' => $file->url
            ];
        }

        return $file->url;
    }

    /**
     * Get the files list with urls.
     *
     * @return array|null
     */
    public function files(Model $model = null)
    {
        $model ??= $this;

        return $model->files->isEmpty()
            ? null :
            $this->files
            ->keyBy('field')
            ->map(fn($file) => $file->url);
    }
}
