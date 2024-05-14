<?php

declare(strict_types=1);

namespace Enpii_Debug\App\Support\Traits;

use Enpii_Debug\App\WP\Enpii_Debug_WP_Plugin;
use Illuminate\Contracts\Container\BindingResolutionException;

trait Enpii_Debug_Trait {
	/**
	 *
	 * @return Enpii_Debug_WP_Plugin
	 * @throws BindingResolutionException
	 */
	public function demoda_wp_plugin(): Enpii_Debug_WP_Plugin {
		return Enpii_Debug_WP_Plugin::wp_app_instance();
	}
}
