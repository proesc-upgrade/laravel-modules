<?php namespace Creolab\LaravelModules;

use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;

/**
 * Single module definition
 * @author Boris Strahija <bstrahija@gmail.com>
 */
class ModuleCollection extends Collection {

	/**
	 * List of all modules
	 * @var array
	 */
	public $items = array();

	/**
	 * IoC
	 * @var Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Initialize a module collection
	 * @param Application|array $app
	 */
	public function __construct($app = null)
	{
		if (is_array($app)) {
			parent::__construct($app);
			// Tenta obter o app do container se disponível
			if (function_exists('app')) {
				$this->app = app();
			}
		} elseif ($app instanceof Application) {
			$this->app = $app;
		} else {
			parent::__construct([]);
			// Tenta obter o app do container se disponível
			if (function_exists('app')) {
				$this->app = app();
			}
		}
	}

	/**
	 * Initialize all modules
	 * @return void
	 */
	public function registerModules()
	{
		// First we need to sort the modules
		$this->sort(function($a, $b) {
			if ($a->order == $b->order) return 0;
			else                        return $a->order < $b->order ? -1 : 1;
		});

		// Then register each one
		foreach ($this->items as $module)
		{
			$module->register();
		}
	}

}
