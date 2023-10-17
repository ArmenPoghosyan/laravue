<?php

namespace App\Http\Controllers;

use App\Models\{Company, User};
use Illuminate\Http\{JsonResponse, Request, Response};
use Illuminate\Support\Facades\{Auth, Hash, Password};

class WebAuthController extends Controller
{
	/**
	 * Init the user data
	 *
	 * @param Request $request
	 * @return mixed
	 */
	public function init(Request $request)
	{
		$user = auth_user();

		if ($user) {
			/** @var Company */
			$company = $user->company;

			if ($company) {
				$company
					->setRelation('settings',		$company->settings->pluck('value', 'key'))
					->setRelation('working_days',	$company->normalized_working_days)
					//
				;
			}

			$user->setRelation('settings',			$user->settings->pluck('value', 'key'));

			return success([
				'user' 		=> $user,
				'company'	=> $company
			]);
		}

		unauthorized();
	}

	/**
	 * Handle an incoming authentication request.
	 *
	 * @param Request $request
	 * @return mixed
	 */
	public function login(Request $request)
	{
		$request->validate([
			'email'		=> 'required|string|email|max:255',
			'password'	=> 'required|string|min:8|max:255',
			'remember'	=> 'boolean'
		], [], []);

		// * Try to find the user by email
		$user = User::where('email', $request->email)->whereNot('type', User::TYPE_USER)->first();

		// * If the user doesn't exist or the password is incorrect
		if (!$user || !Hash::check($request->password, $user->password)) {
			return fail([
				'message' => 'The provided credentials are incorrect.'
			], Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		// * Regenerate the session
		$request->session()->regenerate();

		// * Attempt to log the user in
		$status = Auth::guard('web')->attempt($request->only('email', 'password'), $request->remember);

		if (!$status) {
			return fail([
				'message' => 'The provided credentials are incorrect.'
			], Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		return success();
	}

	/**
	 * Handle an incoming forgot password request.
	 *
	 * @param Request $request
	 * @return mixed
	 */
	public function forgot(Request $request)
	{
		$request->validate([
			'email'		=> 'required|string|email|max:255|exists:users,email',
		], [], ___('globals.user_fields'));

		$status = Password::sendResetLink($request->only('email'));

		switch ($status) {

			case Password::RESET_LINK_SENT:
				return success();

			case Password::RESET_THROTTLED:
				return fail([
					'email' => ['Too many attempts. Please try again later.']
				], Response::HTTP_TOO_MANY_REQUESTS);
		}

		return fail();
	}

	/**
	 * Handle an incoming reset password request.
	 *
	 * @param Request $request
	 * @return mixed
	 */
	public function reset(Request $request)
	{
		$request->validate([
			'token'		=> 'required|string',
			'email'		=> 'required|string|email|max:255|exists:users,email',
			'password'	=> 'required|string|min:8|max:255|confirmed',
		], [], ___('globals.user_fields'));

		$status = Password::reset($request->only('email', 'token', 'password'), function ($user, $password) {
			$user->update(['password' => $password]);
		});

		switch ($status) {
			case Password::PASSWORD_RESET:
				return success();

			default:
				return fail([
					'email' => ['The provided credentials are incorrect.']
				], Response::HTTP_UNPROCESSABLE_ENTITY);
		}
	}

	/**
	 * Handle an incoming logout request.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function logout(Request $request)
	{
		// * Log the user out
		Auth::guard('web')->logout();

		// * Invalidate the session
		$request->session()->invalidate();

		return success();
	}
}
