<?php

declare(strict_types=1);

namespace Enpii_Debug\App\Watchers;

use Enpii_Base\App\Support\Enpii_Base_Helper;
use Enpii_Debug\App\Support\Traits\Fetches_Stack_Trace_For_Wpdb_Trait;
use Illuminate\Database\Events\QueryExecuted;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\Watchers\QueryWatcher;

class Query_Watcher extends QueryWatcher {
	use Fetches_Stack_Trace_For_Wpdb_Trait;

	/**
	 * Record a query was executed.
	 *
	 * @param  \Illuminate\Database\Events\QueryExecuted  $event
	 * @return void
	 */
	public function recordQuery( QueryExecuted $event ) {
		if ( ! Telescope::isRecording() ) {
			return;
		}

		$time = $event->time;
		$caller = $this->getCallerFromStackTrace();
		$query_callstack = isset( $event->connection->getConfig()['query_callstack'] ) && is_string( $event->connection->getConfig()['query_callstack'] ) ? $event->connection->getConfig()['query_callstack'] : null;
		if ( $query_callstack ) {
			$file = $query_callstack;
		} else {
			$file = $caller['file'] ?? null;
		}
		$file = Enpii_Base_Helper::get_current_url() . ', ' . $file;

		if ( $caller ) {
			Telescope::recordQuery(
				IncomingEntry::make(
					[
						'connection' => $event->connectionName,
						'bindings' => [],
						'sql' => $this->replaceBindings( $event ),
						'time' => number_format( $time, 2, '.', '' ),
						'slow' => isset( $this->options['slow'] ) && $time >= $this->options['slow'],
						'file' => $file,
						'line' => $caller['line'],
						'hash' => $this->familyHash( $event ),
					]
				)->tags(
					array_merge(
						[ 'wpdb', Enpii_Base_Helper::get_current_url() ],
						$this->tags( $event )
					)
				)
			);
		}
	}
}
