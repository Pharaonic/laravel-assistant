<?php

namespace Pharaonic\Laravel\Assistant\Http\Resources\Json;

use Illuminate\Database\Eloquent\Model;

class FileableResourceMixin
{
    /**
     * Get the file information.
     *
     * @param  string       $name
     * @param  string       $target
     * @param  boolean      $isTemporary
     * @param  integer|null $expire
     * @param  Model|null   $resource
     * @return array|null
     */
    public function file(string $name, string $target = 'info', bool $isTemporary = false, ?int $expire = null, ?Model $resource = null)
    {
        $resource ??= $this->resource;

        if (!$resource->isRelation('files') || !$resource->relationLoaded('files') || !$resource->{$name}) {
            return null;
        }

        return $resource->{$name}->upload?->info($target, $isTemporary, $expire);
    }

    /**
     * Get the thumbnail information.
     *
     * @param  string       $name
     * @param  string       $target
     * @param  boolean      $isTemporary
     * @param  integer|null $expire
     * @param  Model|null   $resource
     * @return array|null
     */
    public function thumbnail(string $name, string $target = 'info', bool $isTemporary = false, ?int $expire = null, ?Model $resource = null)
    {
        $resource ??= $this->resource;

        if (!$resource->isRelation('files') || !$resource->relationLoaded('files') || !$resource->{$name} || !$resource->{$name}->upload?->thumbnail_id) {
            return null;
        }

        return $resource->{$name}->upload?->thumbnail?->info($target, $isTemporary, $expire);
    }

    /**
     * Get all files information.
     *
     * @param  string       $target
     * @param  boolean      $isTemporary
     * @param  integer|null $expire
     * @param  Model|null   $resource
     * @return array|null
     */
    public function files(string $target = 'info', bool $isTemporary = false, ?int $expire = null, ?Model $resource = null)
    {
        $resource ??= $this->resource;

        if (!$resource->isRelation('files') || !$resource->relationLoaded('files')) {
            return null;
        }

        return $resource->files
            ->mapWithKeys(function ($file) use ($target, $isTemporary, $expire) {
                return [
                    $file->field => $file->upload?->info($target, $isTemporary, $expire)
                ];
            })
            ->toArray();
    }
}
