<?php

namespace App\Notifications\Channels;

use App\Models\UserNotification;
use Illuminate\Notifications\Notification;

class SystemChannel
{
	/**
	 * Send the given notification.
	 *
	 * @param  mixed  $notifiable
	 * @param  \Illuminate\Notifications\Notification  $notification
	 * @return array
	 *
	 * @throws \NotificationChannels\Fcm\Exceptions\CouldNotSendNotification
	 * @throws \Kreait\Firebase\Exception\FirebaseException
	 */
	public function send($notifiable, Notification $notification)
	{
		$notification = UserNotification::create([
			'id'				=> $notification->id,
			'type'				=> $notification::class,
			'channel'			=> UserNotification::CHANNEL_SYSTEM,
			'notifiable_id'		=> $notifiable->id,
			'notifiable_type'	=> $notifiable::class,
			'signature'			=> method_exists($notification, 'signature') ? $notification->signature() : null,
			'data'				=> json_encode($notification->toArray($notifiable)),
			'read_at'			=> null,
		]);

		return $notification;
	}
}
