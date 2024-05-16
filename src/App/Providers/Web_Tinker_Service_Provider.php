<?php

declare(strict_types=1);

namespace Enpii_Debug\App\Providers;

use Spatie\WebTinker\WebTinkerServiceProvider;

class Web_Tinker_Service_Provider extends WebTinkerServiceProvider {
	public function register() {
		$this->fetch_config();
		parent::register();
	}

	protected function fetch_config(): void {
		wp_app_config(
			[
				'web-tinker' => apply_filters(
					'wp_app_web_tinker_config',
					$this->get_default_config()
				),
			]
		);
	}

	protected function get_default_config(): array {
		$config = [
			/*
			 * The web tinker page will be available on this path.
			 */
			'path' => env( 'WEB_TINKER_PATH', ENPII_BASE_WP_APP_PREFIX . '/tinker' ),

			/*
			 * Possible values are 'auto', 'light' and 'dark'.
			 */
			'theme' => 'auto',

			/*
			 * By default this package will only run in local development.
			 * Do not change this, unless you know what you are doing.
			 */
			'enabled' => env( 'WEB_TINKER_ENABLED', true ),

			/*
			* This class can modify the output returned by Tinker. You can replace this with
			* any class that implements \Spatie\WebTinker\OutputModifiers\OutputModifier.
			*/
			'output_modifier' => \Spatie\WebTinker\OutputModifiers\PrefixDateTime::class,

			/*
			* These middleware will be assigned to every WebTinker route, giving you the chance
			* to add your own middlewares to this list or change any of the existing middleware.
			*/
			'middleware' => [
				\Illuminate\Cookie\Middleware\EncryptCookies::class,
				\Illuminate\Session\Middleware\StartSession::class,
				\Enpii_Base\App\Http\Middleware\Authenticate_Is_WP_User_Administrator::class,
			],

			/*
			 * If you want to fine-tune PsySH configuration specify
			 * configuration file name, relative to the root of your
			 * application directory.
			 */
			'config_file' => env( 'PSYSH_CONFIG', null ),
		];

		return $config;
	}
}
