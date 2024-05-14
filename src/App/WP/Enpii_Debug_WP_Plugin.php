<?php

declare(strict_types=1);

namespace Enpii_Debug\App\WP;

use Enpii_Base\App\Support\App_Const;
use Enpii_Base\Deps\Illuminate\Contracts\Container\BindingResolutionException;
use Enpii_Base\Foundation\WP\WP_Plugin;
use Enpii_Debug\App\Queries\Add_Telescope_Web_Tinker_Providers;

class Enpii_Debug_WP_Plugin extends WP_Plugin {
	/**
	 * All hooks shuold be registered here, inside this method
	 * @return void
	 * @throws BindingResolutionException
	 */
	public function manipulate_hooks(): void {
		add_filter( App_Const::FILTER_WP_APP_MAIN_SERVICE_PROVIDERS, [ $this, 'register_service_providers' ] );
	}

	public function register_service_providers( $providers ) {
		return Add_Telescope_Web_Tinker_Providers::execute_now( $providers );
	}

	public function get_name(): string {
		return 'Enpii Debug';
	}

	public function get_version(): string {
		return ENPII_DEBUG_PLUGIN_VERSION;
	}
}
