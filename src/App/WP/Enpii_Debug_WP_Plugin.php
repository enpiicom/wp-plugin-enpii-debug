<?php

declare(strict_types=1);

namespace Enpii_Debug\App\WP;

use Enpii_Base\App\Support\App_Const;
use Enpii_Base\Deps\Illuminate\Contracts\Container\BindingResolutionException;
use Enpii_Base\Foundation\Database\Connectors\Connection_Factory;
use Enpii_Base\Foundation\Database\Wpdb_Connection;
use Enpii_Base\Foundation\WP\WP_Plugin;
use Enpii_Debug\App\Jobs\Setup_App_For_Enpii_Debug;
use Enpii_Debug\App\Queries\Add_Telescope_Web_Tinker_Providers;
use Enpii_Debug\App\Support\Enpii_Debug_Helper;
use Illuminate\Database\Events\QueryExecuted;

class Enpii_Debug_WP_Plugin extends WP_Plugin {
	/**
	 * All hooks shuold be registered here, inside this method
	 * @return void
	 * @throws BindingResolutionException
	 */
	public function manipulate_hooks(): void {
		add_filter( App_Const::FILTER_WP_APP_MAIN_SERVICE_PROVIDERS, [ $this, 'register_service_providers' ] );
		add_filter( 'log_query_custom_data', [ $this, 'watch_wp_queries' ], 10, 5 );


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

	public function watch_wp_queries( $query_data, $query, $query_time, $query_callstack, $query_start ) {
		$config = wp_app_config('database.connections.wpdb');
		$config['query_callstack'] = $query_callstack;
		$config['query_start'] = $query_start;
		$config['query_time'] = $query_time;
		$config['query_data'] = $query_data;
		$config['query'] = $query;
		/** @var \Enpii_Base\Foundation\Database\Connectors\Connection_Factory $connection_factory */
		$connection_factory = wp_app('db.factory');

		/** @var \Enpii_Base\Foundation\Database\Wpdb_Connection $obj_wpdb */
		$obj_wpdb = $connection_factory->make($config, 'wpdb');
		$event = new QueryExecuted($query, $query_data, $query_time, $obj_wpdb);
		wp_app_event($event);
	}
}
