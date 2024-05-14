<?php
/**
 * Plugin Name: Enpii Debug
 * Plugin URI:  https://enpii.com/wp-plugin-enpii-debug/
 * Description: Debug plugin to be used with Enpii Base
 * Author:      dev@enpii.com, nptrac@yahoo.com
 * Author URI:  https://enpii.com/
 * Version:     1.0.0
 * Text Domain: enpii
 */

// Update these constants whenever you bump the version
defined( 'ENPII_DEBUG_PLUGIN_VERSION' ) || define( 'ENPII_DEBUG_PLUGIN_VERSION', '1.0.0' );

// We set the slug for the plugin here.
// This slug will be used to identify the plugin instance from the WP_Application container
defined( 'ENPII_DEBUG_PLUGIN_SLUG' ) || define( 'ENPII_DEBUG_PLUGIN_SLUG', 'enpii-debug' );

// We include the vendor in the repo if there is no vendor loaded before
if ( version_compare( phpversion(), '8.1.0', '<' ) || ENPII_BASE_FORCE_LEGACY ) {
	// Lower that 8.1, we load dependencies for <= 8.0, we use Laravel 7
	$autoload_file = __DIR__ . DIR_SEP . 'vendor-legacy' . DIR_SEP . 'autoload.php';
} else {
	// PHP >= 8.1, we use Laravel 10 as the latest
	$autoload_file = __DIR__ . DIR_SEP . 'vendor' . DIR_SEP . 'autoload.php';
}

if ( file_exists( $autoload_file ) && ! class_exists( \Enpii_Debug\App\WP\Enpii_Debug_WP_Plugin::class ) ) {
	require_once $autoload_file;
}

/**
| We need to check the plugin mandatory requirements first
 */
// It's better to check the prerequisites using the `plugins_loaded`, low priority,
//  rather than the activation hook because there is a case where this plugin is already
//  enabled but then the mandatory prerequisites are disabled after.
// We need to use the hook `plugins_loaded` here rather than put to the WP Plugin class
//  because there is the posibility Enpii Base or other needed plugins not loaded.
add_action(
	'plugins_loaded',
	function () {
		$error_message = '';

		$plugin_slug = plugin_basename( __FILE__ );
		if ( ! $plugin_slug !== ENPII_DEBUG_PLUGIN_SLUG ) {
			$error_message .= $error_message ? '<br />' : '';
			$error_message .= sprintf( __( 'Plugin <strong>%s</strong> folder name must be %.', 'enpii' ), 'Enpii Debug', ENPII_DEBUG_PLUGIN_SLUG );
		}

		if ( ! \Enpii_Debug\App\Support\Enpii_Debug_Helper::check_enpii_base_plugin() ) {
			$error_message .= $error_message ? '<br />' : '';
			$error_message .= sprintf( __( 'Plugin <strong>%s</strong> is required.', 'enpii' ), 'Enpii Base' );
		}

		if ( $error_message ) {
			add_action(
				'admin_notices',
				function () use ( $error_message ) {
					$error_message = sprintf(
						__( 'Plugin <strong>%s</strong> is disabled.', 'enpii' ),
						'Enpii Debug'
					) . '<br />' . $error_message;

					?>
			<div class="notice notice-warning is-dismissible">
					<p><?php echo esc_html( $error_message ); ?></p>
			</div>
					<?php
				}
			);
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}

		/**
		| We initiate the plugin later
		*/
		if ( \Enpii_Debug\App\Support\Enpii_Debug_Helper::check_mandatory_prerequisites() ) {
			// We register Tamara_Checkout_WP_Plugin as a Service Provider
			add_action(
				\Enpii_Base\App\Support\App_Const::ACTION_WP_APP_LOADED,
				function () {
					\Enpii_Debug\App\WP\Enpii_Debug_WP_Plugin::init_with_wp_app(
						DEMODA_PLUGIN_SLUG,
						__DIR__,
						plugin_dir_url( __FILE__ )
					);
				}
			);
		}
	},
	-111
);

