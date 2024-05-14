<?php

declare(strict_types=1);

namespace Enpii_Debug\App\Controllers;

use Enpii_Base\Foundation\Http\Base_Controller;
use Illuminate\Http\JsonResponse;

class Enpii_Debug_Api_Controller extends Base_Controller {
	public function hello(): JsonResponse {
		return wp_app_response()->json(
			[
				'message' => 'Welcome to Enpii Debug API',
			]
		);
	}
}
