<?php

declare(strict_types=1);

namespace Enpii_Debug\App\Queries;

use Enpii_Base\Foundation\Support\Executable_Trait;
use Enpii_Debug\App\Providers\Telescope_Service_Provider;
use Enpii_Debug\App\Providers\Web_Tinker_Service_Provider;

class Add_Telescope_Web_Tinker_Providers {
	use Executable_Trait;

	private $providers = [];

	public function __construct( array $providers ) {
		$this->providers = $providers;
	}

	public function handle(): array {
		$more_providers = [];

		if ( defined( 'WP_APP_TELESCOPE_ENABLED' ) && WP_APP_TELESCOPE_ENABLED ) {
			$more_providers[] = Telescope_Service_Provider::class;
		}

		if ( defined( 'WP_APP_WEB_TINKER_ENABLED' ) && WP_APP_WEB_TINKER_ENABLED ) {
			$more_providers[] = Web_Tinker_Service_Provider::class;
		}
		$more_providers[] = Web_Tinker_Service_Provider::class;

		$providers = array_merge(
			$this->providers,
			$more_providers
		);

		return $providers;
	}
}
