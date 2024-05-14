<?php

declare(strict_types=1);

namespace Enpii_Debug\App\WP;

use Enpii_Base\App\Support\App_Const;
use Enpii_Base\Deps\Illuminate\Contracts\Container\BindingResolutionException;
use Enpii_Base\Foundation\WP\WP_Plugin;
use Enpii_Debug\App\Jobs\Setup_App_For_Enpii_Debug;
use Enpii_Debug\App\Queries\Add_Telescope_Web_Tinker_Providers;
use Enpii_Debug\App\Support\Enpii_Debug_Helper;

class Enpii_Debug_WP_Plugin extends WP_Plugin {
	/**
	 * All hooks shuold be registered here, inside this method
	 * @return void
	 * @throws BindingResolutionException
	 */
	public function manipulate_hooks(): void {
		add_filter( App_Const::FILTER_WP_APP_MAIN_SERVICE_PROVIDERS, [ $this, 'register_service_providers' ] );

		add_action( App_Const::ACTION_WP_APP_SETUP_APP, [ $this, 'setup_app' ]);
		add_action( App_Const::ACTION_WP_APP_MARK_SETUP_APP_DONE, [ $this, 'mark_setup_app_done' ]);
		add_action( App_Const::ACTION_WP_APP_MARK_SETUP_APP_FAILED, [ $this, 'mark_setup_app_failed' ]);
	}

	public function get_name(): string {
		return 'Enpii Debug';
	}

	public function get_version(): string {
		return ENPII_DEBUG_PLUGIN_VERSION;
	}

	public function register_service_providers( $providers ) {
		return Add_Telescope_Web_Tinker_Providers::execute_now( $providers );
	}

	public function setup_app() {
		return Setup_App_For_Enpii_Debug::execute_now();
	}

	public function mark_setup_app_done() {
		update_option( Enpii_Debug_Helper::OPTION_VERSION, ENPII_DEBUG_PLUGIN_VERSION, false );
		delete_option( Enpii_Debug_Helper::OPTION_SETUP_INFO );
	}

	public function mark_setup_app_failed() {
		// We need to flag issue to the db
		update_option( Enpii_Debug_Helper::OPTION_SETUP_INFO, 'failed', false );
	}
}
