<?php

declare(strict_types=1);

namespace Enpii\Demoda\App\Jobs;

use Enpii\Demoda\App\Controllers\Demoda_Api_Controller;
use Enpii_Base\Foundation\Support\Executable_Trait;
use Illuminate\Support\Facades\Route;

class Register_Demoda_Api_Routes {
	use Executable_Trait;

	public function handle(): void {
		Route::get( 'demoda', [ Demoda_Api_Controller::class, 'hello' ] );
	}
}
