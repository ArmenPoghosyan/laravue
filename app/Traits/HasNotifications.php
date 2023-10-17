<?php

namespace App\Traits;

use App\Models\UserNotification;
use Illuminate\Notifications\Notifiable;

trait HasNotifications
{
	use Notifiable;

	/** @return MorphMany  */
	public function notifications()
	{
		return $this->morphMany(UserNotification::class, 'notifiable')
			->where('channel', UserNotification::CHANNEL_USER)
			->latest()
			//
		;
	}

	/**
	 * Unread notifications count
	 *
	 * @param mixed $query
	 * @return void
	 */
	public function scopeUnread_notifications($query)
	{
		$query->addSelect([
			'unread_notifications'	=> function ($query) {
				$query
					->selectRaw('COUNT(*)')
					->from('notifications')
					->where('notifiable_type', User::class)
					->whereColumn('notifiable_id', 'users.id')
					->where('read_at', null)
					//
				;
			}
		]);
	}

	/**
	 * Get all user tokens for push notifications
	 *
	 * @return mixed
	 */
	public function routeNotificationForFcm()
	{
		return $this->devices()->pluck('push_token')->toArray();
	}
}
