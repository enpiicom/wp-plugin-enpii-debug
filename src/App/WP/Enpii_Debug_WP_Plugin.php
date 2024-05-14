<?php

declare(strict_types=1);

namespace Enpii_Debug\App\WP;

use Enpii_Base\Deps\Illuminate\Contracts\Container\BindingResolutionException;
use Enpii_Base\Foundation\WP\WP_Plugin;

class Enpii_Debug_WP_Plugin extends WP_Plugin {
	/**
	 * All hooks shuold be registered here, inside this method
	 * @return void
	 * @throws BindingResolutionException
	 */
	public function manipulate_hooks(): void {
	}

	public function get_name(): string {
		return 'Enpii Debug';
	}

	public function get_version(): string {
		return ENPII_DEBUG_PLUGIN_VERSION;
	}
}
