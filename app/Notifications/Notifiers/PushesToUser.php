<?php

namespace App\Notifications\Notifiers;

use App\Models\User;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\{AndroidConfig, ApnsConfig, AndroidFcmOptions, AndroidNotification, ApnsFcmOptions, Notification as ResourcesNotification};

trait PushesToUser
{
	public function toFcm($notifiable)
	{
		$data = $this->toArray($notifiable);

		$title = config('app.name');
		if (isset($data['title']) && $data['title']) {
			$title = $data['title'];
		} else if ($notifiable instanceof User) {
			$title = $notifiable->company->name;
		}

		return FcmMessage::create()
			->setNotification(
				ResourcesNotification::create()
					->setTitle($title)
					->setBody($data['message'])
				//
			)
			->setAndroid(
				AndroidConfig::create()
					->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('analytics'))
					->setNotification(
						AndroidNotification::create()->setColor('#24836F')->setSound('default')
					)
				//
			)
			->setApns(
				ApnsConfig::create()
					->setPayload([
						'aps' => [
							'sound' => 'default',
						],
					])
					->setFcmOptions(ApnsFcmOptions::create()->setAnalyticsLabel('analytics_ios'))
				//
			)
			->setData($data)
			//
		;
	}
}
