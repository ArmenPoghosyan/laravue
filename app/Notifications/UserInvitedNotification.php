<?php

namespace App\Notifications;

use App\Helpers\UserMailMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class UserInvitedNotification extends Notification
{
	use Queueable;

	private String $password;

	/**
	 * Create a new notification instance.
	 */
	public function __construct(String $password)
	{
		$this->password = $password;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @return array<int, string>
	 */
	public function via(object $notifiable): array
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 */
	public function toMail(object $notifiable)
	{
		/** @var User */
		$user = $notifiable;

		$localizations	= "emails.user.invite.web";
		$replace		= [
			'app_name'			=> config('app.name'),
			'app_url'			=> config('app.url'),
			'user_email'		=> $user->email,
			'user_password'		=> $this->password,
		];

		return (new UserMailMessage($notifiable))
			->subject(___("{$localizations}.subject", $replace))
			->line(new HtmlString(___("{$localizations}.body", $replace)))
			//
		;
	}
}
