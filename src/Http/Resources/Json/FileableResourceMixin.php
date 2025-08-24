<?php

namespace Pharaonic\Laravel\Assistant\Http\Resources\Json;

use Illuminate\Database\Eloquent\Model;

class FileableResourceMixin
{
    public function thumbnail()
    {
        return function (string $name, string $target = 'info', bool $isTemporary = false, ?int $expire = null, ?Model $resource = null) {
            $resource ??= $this->{'resource'};

            if (!$resource->isRelation('files') || !$resource->relationLoaded('files') || !$resource->{$name} || !$resource->{$name}->upload?->thumbnail_id) {
                return null;
            }

            return $resource->{$name}->upload?->thumbnail?->info($target, $isTemporary, $expire);
        };
    }

    public function file()
    {
        return function (string $name, string $target = 'info', bool $isTemporary = false, ?int $expire = null, ?Model $resource = null) {
            $resource ??= $this->{'resource'};

            if (!$resource->isRelation('files') || !$resource->relationLoaded('files') || !$resource->{$name}) {
                return null;
            }

            return $resource->{$name}->upload?->info($target, $isTemporary, $expire);
        };
    }

    public function files()
    {
        return function (string $target = 'info', bool $isTemporary = false, ?int $expire = null, ?Model $resource = null) {
            $resource ??= $this->{'resource'};

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
        };
    }
}
