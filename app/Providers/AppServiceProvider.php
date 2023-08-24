<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Customizations\CustomFilesystem;
use Spatie\MediaLibrary\MediaCollections\Filesystem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Filesystem::class, CustomFilesystem::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
