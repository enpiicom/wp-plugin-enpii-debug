<?php

declare(strict_types=1);

namespace Enpii_Debug\App\Controllers;

use Enpii_Base\Foundation\Http\Base_Controller;
use Enpii_Debug\App\WP\Enpii_Debug_WP_Plugin;

class Enpii_Debug_Controller extends Base_Controller {
	public function hello( Enpii_Debug_WP_Plugin $enpii_debug_wp_plugin ) {
		return 'hello';
	}
}
