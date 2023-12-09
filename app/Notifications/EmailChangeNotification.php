<?php

namespace App\Notifications;

use App\Helpers\UserMailMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;

class EmailChangeNotification extends Notification
{
	use Queueable;

	private User $user;
	private string $email;

	/**
	 * Create a new notification instance.
	 */
	public function __construct(User $user, string $email)
	{
		$this->user		= $user;
		$this->email	= $email;
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
	public function toMail(object $notifiable): MailMessage
	{
		return (new UserMailMessage($notifiable))
			->subject(new HtmlString(___('emails.user.change_email.subject')))
			->line(new HtmlString(___('emails.user.change_email.body')))
			->action(new HtmlString(___('emails.user.change_email.action')), $this->change_route())
			->line(new HtmlString(___('emails.user.change_email.outro', ['time' => 1])))
			//
		;
	}

	private function change_route()
	{
		return URL::temporarySignedRoute('email.verify', 60 * 60, [
			'user_id'	=> $this->user->id,
			'email'		=> $this->email,
		]);
	}
}
