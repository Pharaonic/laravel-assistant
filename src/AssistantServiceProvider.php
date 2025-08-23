<?php

namespace Pharaonic\Laravel\Assistant;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

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
    }
}
