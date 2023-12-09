<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class NotAllowed extends Exception
{
	public function render()
	{
		return fail([
			'code' 		=> 'not_allowed',
			'message'	=> ___('globals.errors.not_allowed')
		], Response::HTTP_FORBIDDEN);
	}
}
