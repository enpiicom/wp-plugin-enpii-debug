<?php

declare(strict_types=1);

namespace Enpii_Debug\App\Support;

class Enpii_Debug_Helper {
	public static function check_mandatory_prerequisites(): bool {
		return version_compare( phpversion(), '7.3.0', '>=' );
	}

	public static function check_enpii_base_plugin(): bool {
		return (bool) class_exists( \Enpii_Base\App\WP\WP_Application::class );
	}
}
