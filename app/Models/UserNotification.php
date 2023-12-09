<?php

namespace App\Models;


use Illuminate\Notifications\DatabaseNotification;

class UserNotification extends DatabaseNotification
{
	const CHANNEL_SYSTEM			= 'system';
	const CHANNEL_USER				= 'user';


	protected $hidden = [
		'type',
		'notifiable_type',
		'notifiable_id',
		'updated_at'
	];

	protected $appends = [
		'is_read'
	];

	/**
	 * Check if system notification was sent
	 *
	 * @param mixed $signature
	 * @return mixed
	 */
	public static function system_sent($signature = null)
	{
		return UserNotification::where('signature', $signature)->exists();
	}

	/**
	 * Get is read attribute
	 *
	 * @return bool
	 */
	public function getIsReadAttribute()
	{
		return $this->read_at !== null;
	}
}
