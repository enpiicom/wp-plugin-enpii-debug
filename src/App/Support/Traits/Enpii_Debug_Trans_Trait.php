<?php

declare(strict_types=1);

namespace Enpii\Demoda\App\Support\Traits;

trait Enpii_Debug_Trans_Trait {
	// phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
	protected function __( $untranslated_message ): string {
		// phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
		return __( $untranslated_message, 'enpii' );
	}
}
