<?php

namespace App\Models;


use Illuminate\Notifications\DatabaseNotification;

class UserNotification extends DatabaseNotification
{
	const CHANNEL_SYSTEM			= 'system';
	const CHANNEL_USER				= 'user';

	const TYPE_ADMIN_PUSH			= 'admin_push';
	const TYPE_FIRST_BREAK			= 'first_break';
	const TYPE_SECOND_BREAK			= 'second_break';
	const TYPE_LUNCH				= 'lunch';
	const TYPE_BACK_TO_WORK			= 'back_to_work';
	const TYPE_WORKING_DAY_END		= 'working_day_end';

	const TYPE_LEAGUE_CHANGED		= 'league_changed';
	const TYPE_TASK_HALF_LEFT		= 'task_half_left';
	const TYPE_TASK_QUARTER_LEFT	= 'task_quarter_left';

	const TYPE_BONUS_GIVEN			= 'bonus_given';

	protected $hidden = [
		'type',
		'notifiable_type',
		'notifiable_id',
		'updated_at'
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
}
