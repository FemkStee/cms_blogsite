<?php

namespace Femst\Like;

use Statamic\Facades\CP\Nav;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $namespace = 'App\Http\Controllers';

    protected $tags = [
        Tags\Like::class,
    ];

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
        'actions' => __DIR__.'/../routes/actions.php',
    ];

    protected function createNavigation()
    {
        Nav::extend(function ($nav) {
            $nav->create('Settings')
                ->section('like')
                ->route('like.index');
                // ->icon('shopping-cart'); // een icoon
        });
    }

    public function bootAddon()
    {
        $this->createNavigation();
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'like');
    }
}
