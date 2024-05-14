<?php

declare(strict_types=1);

namespace Enpii_Debug\App\Support;

use Enpii_Base\App\Support\Enpii_Base_Helper;

class Enpii_Debug_Helper {
	const OPTION_VERSION = '_enpii_debug_version';
	const OPTION_SETUP_INFO = '_enpii_debug_setup_info';

	public static function check_mandatory_prerequisites(): bool {
		return version_compare( phpversion(), '7.3.0', '>=' );
	}

	public static function check_enpii_base_plugin(): bool {
		return (bool) class_exists( \Enpii_Base\App\WP\WP_Application::class );
	}

	public static function maybe_redirect_to_setup_app() {
		$version_option = get_option( static::OPTION_VERSION );
		if ( ( empty( $version_option ) ) ) {
			// We only want to redirect if the setup did not fail previously
			if ( ! static::wp_app_setup_failed() ) {
				Enpii_Base_Helper::redirect_to_setup_url();
			}
		}
	}

	public static function wp_app_setup_failed(): bool {
		return (string) get_option( static::OPTION_SETUP_INFO ) === 'failed';
	}
}
