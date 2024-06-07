<?php

namespace App\Http\Controllers;

use App\Models\{Multimedia, User};
use App\Notifications\EmailChangeNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function index($user_id = null)
	{
		if ($user_id) {
			/** @var User */
			$user = User::findOrFail($user_id);
		} else {
			$user				= auth_user(true);
			$user['settings']	= $user->settings()->pluck('value', 'key');
		}

		return success([
			'user'			=> $user
		]);
	}

	public function update(Request $request)
	{
		$user			= auth_user();

		$fields			= [];
		$validations	= [
			'first_name'	=> 'required|string|max:255',
			'last_name'		=> 'required|string|max:255',
			'phone'			=> 'required|string|max:255',
			'birth_date'	=> 'required|date',
		];

		if ($request->has('first_name')) {
			$fields[] = 'first_name';
		}

		if ($request->has('last_name')) {
			$fields[] = 'last_name';
		}

		if ($request->has('phone')) {
			$fields[] = 'phone';
		}

		if ($request->has('birth_date')) {
			$fields[] = 'birth_date';
		}

		$request->validate(
			collect($fields)->mapWithKeys(fn ($field) => [$field => $validations[$field]])->toArray(),
			[],
			___('globals.user_fields')
		);

		if ($request->hasFile('avatar')) {
			$multimedia = Multimedia::handle_request_file('avatar', Multimedia::TYPE_PHOTO);

			if ($multimedia) {
				$user->update([
					'avatar'	=> $multimedia->path
				]);
			}
		}

		foreach ($fields as $field) {
			$user->setAttribute($field, $request->get($field));
		}

		$user->update([
			'email_verified_at'	=> $user->is_first_start ? now() : $user->email_verified_at,
		]);

		return success([
			'user'	=> $user
		]);
	}

	public function check_password(Request $request)
	{
		$request->validate([
			'password'	=> 'required|string|max:255',
		], [], ___('globals.user_fields'));

		if (auth_user()->check_password($request->password)) {
			return success();
		}

		return fail([
			'errors'	=> [
				'password'	=> [___('validation.password.incorrect')]
			]
		], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
	}

	public function update_password(Request $request)
	{
		$request->validate([
			'password'	=> 'required|string|min:8|max:255|confirmed',
		], [], ___('globals.user_fields'));

		auth_user()->update([
			'password'				=> $request->password,
			'force_password_change'	=> false,
		]);

		return success();
	}

	public function change_email(Request $request)
	{
		$request->validate([
			'email'	=> 'required|string|email|max:255|unique:users,email',
		], [],  ___('globals.user_fields'));

		try {
			Notification::route('mail', $request->email)->notify(new EmailChangeNotification(auth_user(), $request->email));
		} catch (\Throwable $th) {
			return $th->getMessage();
			return fail();
		}


		return success();
	}

	public function update_email(Request $request)
	{
		if (!$request->hasValidSignature()) {
			abort(404);
		}

		$user = User::findOrFail($request->user_id);

		$user->update([
			'email'	=> $request->email
		]);

		return redirect('/');
	}
}
