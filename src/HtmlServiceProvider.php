<?php

namespace Sohamgreens\Jqvalidator;

use Illuminate\Support\ServiceProvider;

class HtmlServiceProvider extends \Collective\Html\HtmlServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->publishes([
            __DIR__.'/../resources' => base_path('public/vendor/sohamgreens/jqvalidator'),
        ]);
        //$this->package('sohamgreens/jqvalidator');
    }
    
    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function ($app) {
            $form = new FormBuilder($app['html'], $app['url'], $app['view'], $app['session.store']->token());

            return $form->setSessionStore($app['session.store']);
        });
    }

}
