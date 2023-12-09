<?php

namespace App\Listeners;

use App\Models\UserDevice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class InvalidFCMTokenListener
{
	/**
	 * Handle the event.
	 */
	public function handle(object $event): void
	{
		$tokens = $event?->data['token'] ?? [];
		if (count($tokens)) {
			UserDevice::whereIn('push_token', $tokens)->delete();
		}
	}
}
