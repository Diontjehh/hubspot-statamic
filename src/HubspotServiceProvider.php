<?php

namespace DionBoerrigter\Hubspot;

use DionBoerrigter\Hubspot\Listeners\FormSubmittedListener;
use Illuminate\Support\Facades\Event;
use Statamic\Events\SubmissionCreated;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Statamic\Providers\AddonServiceProvider;

class HubspotServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    public function boot()
    {
        parent::boot();

        Event::listen(SubmissionCreated::class, FormSubmittedListener::class);

        $this->loadRoutes();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'hubspot');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->bootAddon();
    }

    public function bootAddon()
    {
        Permission::extend(function () {
            Permission::group('hubspot', 'Hubspot', function () {
                Permission::register('manage hubspot settings')
                    ->label('Manage Hubspot Settings')
                    ->description('Manage preferences of Hubspot');
            });
        });

        Nav::extend(function ($nav) {
            $nav->content('Hubspot')
                ->url('hubspot/forms')
                ->icon('angle-brackets-dots')
                ->can('manage hubspot settings')
                ->children([
                    'Forms' => route('forms.index'),
                    'Fields' => route('fields.index'),
                ]);
        });
    }

    protected function loadRoutes()
    {
        foreach ($this->routes as $type => $path) {
            $this->loadRoutesFrom($path, $type);
        }
    }
}
