<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ValidationException extends Exception
{
	private $errors = [];
	public function __construct(array $errors)
	{
		$this->errors = $errors;
	}

	public function render()
	{
		return fail(['errors' => $this->errors], Response::HTTP_UNPROCESSABLE_ENTITY);
	}
}
