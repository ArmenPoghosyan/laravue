<?php

namespace App\Notifications;

use App\Helpers\UserMailMessage;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;

class ResetPasswordNotification extends ResetPassword
{
	use Queueable;

	public function __construct($token)
	{
		parent::__construct($token);
		static::$toMailCallback = fn ($notifiable) => $this->toMail($notifiable);
	}

	public function toMail($notifiable)
	{
		return (new UserMailMessage($notifiable))
			->subject(new HtmlString(___('emails.reset_password.subject')))
			->line(new HtmlString(___('emails.reset_password.body')))
			->action(new HtmlString(___('emails.reset_password.action')), $this->resetUrl($notifiable))
			->line(new HtmlString(___('emails.reset_password.outro')))
			//
		;
	}
}
