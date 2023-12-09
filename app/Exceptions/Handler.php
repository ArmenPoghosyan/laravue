<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
	/**
	 * The list of the inputs that are never flashed to the session on validation exceptions.
	 *
	 * @var array<int, string>
	 */
	protected $dontFlash = [
		'current_password',
		'password',
		'password_confirmation',
	];

	protected $dontReport = [
		NotAllowed::class,
		UnauthorizedException::class,
	];

	/**
	 * Register the exception handling callbacks for the application.
	 */
	public function register(): void
	{
		$this->reportable(function (Throwable $e) {
			//
		});
	}

	public function render($request, Throwable $exception)
	{
		switch (true) {
			case $exception instanceof ModelNotFoundException:
			case $exception instanceof NotFoundHttpException:
				if (request()->wantsJson()) {
					return fail([
						'message'	=> 'Resource not found',
					], Response::HTTP_NOT_FOUND);
				} else {
					return redirect()->to('/404');
				}

			case $exception instanceof ValidationException:
				/** @var ValidationException */
				$exception = $exception;
				return fail([
					'errors'	=> $exception->errors(),
				], Response::HTTP_UNPROCESSABLE_ENTITY);

			case $exception instanceof UnauthorizedException:
				return fail([
					'message'	=> 'Unauthorized',
				], Response::HTTP_UNAUTHORIZED);

			case $exception instanceof TokenMismatchException:
				return fail([
					'message'	=> $exception->getMessage(),
				], 419);

			case $exception instanceof MethodNotAllowedHttpException:
				return fail([
					'message'	=> 'Method not allowed',
				], Response::HTTP_METHOD_NOT_ALLOWED);

				// case $exception instanceof QueryException:
				// 	/** @var QueryException */
				// 	$exception = $exception;
				// 	$data = ['message' => 'Query exception'];

				// 	if (app()->environment('local')) {
				// 		$data['sql'] = $exception->getSql();
				// 	}

				// 	return fail($data, Response::HTTP_INTERNAL_SERVER_ERROR);
		}

		return parent::render($request, $exception);
	}
}
