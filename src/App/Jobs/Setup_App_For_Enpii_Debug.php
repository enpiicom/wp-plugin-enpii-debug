<?php

declare(strict_types=1);

namespace Enpii_Debug\App\Jobs;

use Enpii_Base\Foundation\Support\Executable_Trait;
use Illuminate\Support\Facades\Artisan;

class Setup_App_For_Enpii_Debug {
	use Executable_Trait;

	public function handle(): void {
		// We only want to publish `telescope-assets` here.
		//	Using `telescope:publish`would publich `telescope-config` as well.
		//	We load migration rules from the plugin folder so we don't need to publish `telescope-migrations`.
		Artisan::call('vendor:publish', [
            '--tag' => 'telescope-assets',
            '--force' => true,
        ]);

		Artisan::call(
			'web-tinker:install',
			[]
		);

		if ( wp_app_config( 'app.debug' ) ) {
			$output = Artisan::output();
			echo( esc_html( $output ) );
		}
	}
}
