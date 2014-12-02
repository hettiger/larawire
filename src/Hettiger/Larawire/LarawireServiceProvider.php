<?php namespace Hettiger\Larawire;

use Hettiger\Larawire\Commands\InstallCommand;
use Illuminate\Support\ServiceProvider;

class LarawireServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider. (Called immediately when the service provider is registered)
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

    /**
     * Boot the service provider. (Called right before a request is routed)
     *
     * @return void
     */
    public function boot()
    {
        $this->package('hettiger/larawire');
        $this->registerCommands();
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

    /**
     * Register Commands provided by this package
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->app->bind('larawire.install', function($app) {
            return new InstallCommand();
        });

        $this->commands('larawire.install');
    }

}
