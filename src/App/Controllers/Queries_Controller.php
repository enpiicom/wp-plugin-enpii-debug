<?php

declare(strict_types=1);

namespace Enpii_Debug\App\Controllers;

use Enpii_Debug\App\Watchers\Query_Watcher;
use Laravel\Telescope\Http\Controllers\QueriesController;

class Queries_Controller extends QueriesController {
	/**
	 * The watcher class for the controller.
	 *
	 * @return string
	 */
	protected function watcher() {
		return Query_Watcher::class;
	}
}
