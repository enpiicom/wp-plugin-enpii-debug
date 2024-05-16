<?php
use Enpii\Demoda\App\WP\Demoda_WP_Plugin;
?>@extends('demoda::demoda/layout')

@section('content')
	<p>
		<?php
		echo esc_html(
			sprintf(
				// translators: %s is the name of the plugin
				__( 'Hello from %s plugin.', 'enpii' ),
				Demoda_WP_Plugin::wp_app_instance()->get_name(),
			)
		);
		?>
	</p>
@endsection
