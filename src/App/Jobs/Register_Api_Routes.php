<?php

declare(strict_types=1);

namespace Enpii_Debug\App\Jobs;

use Enpii_Base\Foundation\Support\Executable_Trait;
use Enpii_Debug\App\Controllers\Enpii_Debug_Api_Controller;
use Illuminate\Support\Facades\Route;

class Register_Api_Routes {
	use Executable_Trait;

	public function handle(): void {
		Route::get( 'enpii-debug', [ Enpii_Debug_Api_Controller::class, 'hello' ] );
	}
}
