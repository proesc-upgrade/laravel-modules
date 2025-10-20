<?php namespace Creolab\LaravelModules;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 * @return void
	 */
	public function boot()
	{
		// Publish configuration
		$this->publishes([
			__DIR__.'/config/config.php' => config_path('modules.php'),
		], 'config');

		// Register commands
		$this->bootCommands();

		try
		{
			// Auto scan if specified
			$this->app['modules']->start();

			// And finally register all modules
			$this->app['modules']->register();
		}
		catch (\Exception $e)
		{
			$this->app['modules']->logError("There was an error when starting modules: [".$e->getMessage()."]");
		}
	}

	/**
	 * Register the service provider.
	 * @return void
	 */
	public function register()
	{
		// Merge configuration
		$this->mergeConfigFrom(__DIR__.'/config/config.php', 'modules');

		// Register IoC bindings
		$this->app->singleton('modules', function($app)
		{
			return new Finder($app, $app['files'], $app['config']);
		});
	}

	/**
	 * Register all available commands
	 * @return void
	 */
	public function bootCommands()
	{
		// Add modules command
		$this->app->singleton('modules.list', function($app)
		{
			return new Commands\ModulesCommand($app);
		});

		// Add scan command
		$this->app->singleton('modules.scan', function($app)
		{
			return new Commands\ModulesScanCommand($app);
		});

		// Add publish command
		$this->app->singleton('modules.publish', function($app)
		{
			return new Commands\ModulesPublishCommand($app);
		});

		// Add migrate command
		$this->app->singleton('modules.migrate', function($app)
		{
			return new Commands\ModulesMigrateCommand($app);
		});

		// Add seed command
		$this->app->singleton('modules.seed', function($app)
		{
			return new Commands\ModulesSeedCommand($app);
		});

		// Add create command
		$this->app->singleton('modules.create', function($app)
		{
			return new Commands\ModulesCreateCommand($app);
		});

		// Add generate command
		$this->app->singleton('modules.generate', function($app)
		{
			return new Commands\ModulesGenerateCommand($app);
		});

		// Now register all the commands
		$this->commands(array(
			'modules.list',
			'modules.scan',
			'modules.publish',
			'modules.migrate',
			'modules.seed',
			'modules.create',
			'modules.generate'
		));
	}

	/**
	 * Provided service
	 * @return array
	 */
	public function provides()
	{
		return array('Modules');
	}

}
