<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;

class UserMailMessage extends MailMessage
{
	public function __construct($notifiable)
	{
		if ($notifiable instanceof User) {
			$this->viewData['user_name'] = $notifiable->first_name;
		}
	}
}
