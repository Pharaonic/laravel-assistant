<?php

namespace Pharaonic\Laravel\Assistant;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Pharaonic\Laravel\Assistant\Http\Resources\Json\TimeableResourceMixin;

class AssistantServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'pharaonic.assistant');
    }

    /**
     * Bootstrap services.
     *
     * @return void
    */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            AboutCommand::add('Pharaonic', fn () => ['Assistant' => '2.x']);

            $this->publishes(
                [__DIR__.'/../config/config.php' => config_path('pharaonic/assistant.php')],
                ['config', 'pharaonic', 'assistant']
            );
        }

        // Timeable Feature
        if (config('pharaonic.assistant.timeable')) {
            JsonResource::mixin(new TimeableResourceMixin());
        }
    }
}
