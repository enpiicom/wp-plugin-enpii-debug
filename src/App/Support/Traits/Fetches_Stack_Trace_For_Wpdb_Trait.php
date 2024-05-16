<?php

declare(strict_types=1);

namespace Enpii_Debug\App\Support\Traits;

use Illuminate\Support\Str;

trait Fetches_Stack_Trace_For_Wpdb_Trait {
	/**
     * Find the first frame in the stack trace outside of Telescope/Laravel.
     *
     * @return array
     */
    protected function getCallerFromStackTrace()
    {
        $traces = collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS))->forget(0);
		// devd($traces);
		// foreach ($traces as $trace) {
		// 	devd($trace);
		// }
		// devdd($traces);

        return $traces->first(function ($frame) {
            if (! isset($frame['file'])) {
                return false;
            }

            return ! Str::contains($frame['file'],
                base_path('vendor'.DIRECTORY_SEPARATOR.$this->ignoredVendorPath())
            );
        });
    }
}
