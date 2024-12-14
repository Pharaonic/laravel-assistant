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
        // 
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Information
        AboutCommand::add('Pharaonic', fn() => ['Assistant' => '1.x']);
    }
}
