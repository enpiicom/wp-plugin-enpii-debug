<?php

declare(strict_types=1);

namespace Enpii\Demoda\App\Controllers;

use Enpii\Demoda\App\WP\Demoda_WP_Plugin;
use Enpii_Base\Foundation\Http\Base_Controller;

class Demoda_Controller extends Base_Controller {
	public function hello( Demoda_WP_Plugin $demoda_wp_plugin ) {
		return $demoda_wp_plugin->view( 'demoda/hello' );
	}
}
