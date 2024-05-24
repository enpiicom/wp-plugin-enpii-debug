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

/**
| We need to check the plugin mandatory requirements first
|
 */
// It's better to check the prerequisites using the `plugins_loaded`,
//  low weight (for high priority),
//  rather than the activation hook because there is a case when this plugin is already
//  enabled but then the mandatory prerequisites are disabled after.
// We need to use the hook `enpii_base_wp_app_loaded` to ensure Enpii Base to be loaded.
add_action(
	'plugins_loaded',
	function () {
		add_action(
			'enpii_base_wp_app_loaded',
			function () {
				$autoload_file = __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

				if ( ! class_exists( \Enpii_Debug\App\WP\Enpii_Debug_WP_Plugin::class ) ) {
					require_once $autoload_file;
				}

				$error_message = '';

				$plugin_slug = plugin_basename( __DIR__ );
				if ( $plugin_slug !== ENPII_DEBUG_PLUGIN_SLUG ) {
					$error_message .= $error_message ? '<br />' : '';
					// translators: %1$s is the plugin name, %2$s is the plugin slug
					$error_message .= sprintf( __( 'Plugin <strong>%1$s</strong> folder name must be %2$s.', 'enpii' ), 'Enpii Debug', ENPII_DEBUG_PLUGIN_SLUG );
				}

				if ( ! \Enpii_Debug\App\Support\Enpii_Debug_Helper::check_enpii_base_plugin() ) {
					$error_message .= $error_message ? '<br />' : '';
					// translators: %s is the plugin name
					$error_message .= sprintf( __( 'Plugin <strong>%s</strong> is required.', 'enpii' ), 'Enpii Base' );
				}

				if ( ! class_exists( 'WP_CLI' ) ) {
					// We want to redirect to setup app before we init the plugin
					\Enpii_Debug\App\Support\Enpii_Debug_Helper::maybe_redirect_to_setup_app();
				}

				if ( $error_message ) {
					add_action(
						'admin_notices',
						function () use ( $error_message ) {
							$error_message = sprintf(
								// translators: %s is the plugin name
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
				$check_result = \Enpii_Debug\App\Support\Enpii_Debug_Helper::check_mandatory_prerequisites();
				if ( $check_result === true ) {
					// We register Tamara_Checkout_WP_Plugin as a Service Provider
					\Enpii_Debug\App\WP\Enpii_Debug_WP_Plugin::init_with_wp_app(
						ENPII_DEBUG_PLUGIN_SLUG,
						__DIR__,
						plugin_dir_url( __FILE__ )
					);
				}
			}
		);
	},
	-111
);

