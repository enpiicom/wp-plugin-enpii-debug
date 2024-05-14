<?php

declare(strict_types=1);

namespace Enpii\Demoda\App\Jobs;

use Enpii_Base\Foundation\Shared\Traits\Config_Trait;
use Enpii_Base\Foundation\Support\Executable_Trait;

class Write_Meta_Tag {
	use Config_Trait;
	use Executable_Trait;

	private $version;

	public function __construct( array $config ) {
		$this->bind_config( $config, true );
	}

	public function handle(): void {
		printf( '<meta name="generator" content="Demoda %s" />', esc_attr( $this->version ) );
	}
}
