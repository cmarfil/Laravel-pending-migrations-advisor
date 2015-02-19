<?php namespace Cmarfil\PendingMigrationAdvisor;

use Illuminate\Support\ServiceProvider;

class PendingMigrationAdvisorServiceProvider extends ServiceProvider {

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
	public function boot()
	{
		$app = $this->app;

		$this->package('cmarfil/pending-migration-advisor');

		if(!$app->runningInConsole()){
            $app['router']->after(
                function ($request, $response) use ($app) {
                    /** @var LaravelDebugbar $debugbar */
                    $migrationAdvisor = $app['Cmarfil\PendingMigrationAdvisor\MigrationAdvisor'];
                    $migrationAdvisor->modifyResponse($request, $response);
                }
            );
		}

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['config']->package('cmarfil/pending-migration-advisor', __DIR__.'/../../config', 'cmarfil/pending-migration-advisor');
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

}
