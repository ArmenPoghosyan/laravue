<?php

use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Models\{User, Company, UserDevice};
use Illuminate\Http\Request;

#region User
function auth_user($with_all = false): ?User
{
	if ($user = auth()->user()) {
		if ($with_all) {
			$user = User::with_all()->find($user->id);
		}

		return $user;
	}

	return null;
}

function user_id(): ?int
{
	return auth_user()?->id ?? null;
}

function unauthorized()
{
	throw new \Illuminate\Validation\UnauthorizedException();
}
#endregion

#region Status
function status($status, $data = [], $code = 200)
{
	return new JsonResponse(
		['status' => $status] + $data,
		$code
	);
}

function success($data = [], $code = 200)
{
	return status(true, $data, $code);
}

function fail($data = [], $code = 200)
{
	return status(false, $data, $code);
}
#endregion

function get_page_limit($limit = 20)
{
	return request()->get('limit', $limit);
}


/**
 * Get localization
 *
 * @param mixed $path
 * @return mixed
 */
function ___($path, $replace = [], $locale = null)
{
	$parts	= explode(".", $path);
	$base	= __(current($parts), [], $locale);
	unset($parts[0]);

	foreach ($parts as $part) {
		// Allows specification of indexes
		if (is_numeric($part)) {
			$part = intval($part);
		}

		// If the key has not been found, return the initial parameter like __()
		try {
			$base = $base[$part];
		} catch (\Throwable $th) {
			return $path;
		}
	}

	if ($base) {
		if (empty($replace)) {
			return $base;
		}

		$shouldReplace = [];
		foreach ($replace as $key => $value) {
			$shouldReplace[':' . Str::ucfirst($key)] = Str::ucfirst($value);
			$shouldReplace[':' . Str::upper($key)] = Str::upper($value);
			$shouldReplace[':' . $key] = $value;
			$shouldReplace['{' . $key . '}'] = $value;
			$shouldReplace['{website_url}'] = url('/');
		}

		return strtr($base, $shouldReplace);
	}

	return $base;
}


/**
 * Create or update user device info
 *
 * @param User|null $user
 * @param Request|null $request
 * @param bool $auto_create
 * @return null|UserDevice
 */
function process_user_device(User $user = null, Request $request = null, bool $delete = false): UserDevice|bool
{
	$user = $user ?? auth_user();
	if (!$user) return null;

	/** @var Request */
	$request = $request ?? request();

	if ($request->has('device_info')) {
		$device_id		= $request->input('device_info.device_id');
		$device_name	= $request->input('device_info.device_name');
		$device_type	= $request->input('device_info.device_type');
		$push_token		= $request->input('device_info.token');

		if (!$device_id || !$device_type || !$push_token) return null;

		if ($delete) {
			$user->devices()
				->where('device_id', $device_id)
				->where('device_type', $device_type)
				->delete()
				//
			;
			return true;
		}

		$device = $user->devices()->updateOrCreate([
			'device_id'		=> $device_id,
			'device_type'	=> $device_type,
		], [
			'device_name'	=> $device_name,
			'push_token'	=> $push_token,
		]);

		return $device ?? null;
	}

	return null;
}


/**
 * Convert seconds to human readable format
 *
 * @param mixed $seconds
 * @param string $locale
 * @return string
 */
function plural_seconds($seconds, $locale = 'en')
{
	$result		= [];

	$hours		= floor($seconds / 3600);
	$minutes	= floor(($seconds / 60) % 60);
	$seconds	= $seconds % 60;


	switch ($locale) {
		case 'ru':
			$hour_titles		= ['час', 'часа', 'часов'];
			$minute_titles		= ['минута', 'минуты', 'минут'];
			$second_titles		= ['секунда', 'секунды', 'секунд'];

			$cases = [2, 0, 1, 1, 1, 2];

			if ($hours > 0) {
				$result[] = $hours;
				$result[] = $hour_titles[($hours % 100 > 4 && $hours % 100 < 20) ? 2 : $cases[min($hours % 10, 5)]];
			}

			if ($minutes > 0) {
				$result[] = $minutes;
				$result[] = $minute_titles[($minutes % 100 > 4 && $minutes % 100 < 20) ? 2 : $cases[min($minutes % 10, 5)]];
			}

			if ($seconds > 0 && $hours == 0 && $minutes == 0) {
				$result[] = $seconds;
				$result[] = $second_titles[($seconds % 100 > 4 && $seconds % 100 < 20) ? 2 : $cases[min($seconds % 10, 5)]];
			}
			break;

		case 'en':
		default:
			if ($hours > 0) {
				$result[] = $hours;
				$result[] = $hours > 1 ? 'hours' : 'hour';
			}

			if ($minutes > 0) {
				$result[] = $minutes;
				$result[] = $minutes > 1 ? 'minutes' : 'minute';
			}

			if ($seconds > 0 && $hours == 0 && $minutes == 0) {
				$result[] = $seconds;
				$result[] = $seconds > 1 ? 'seconds' : 'second';
			}
			break;
	}

	return implode(' ', $result);
}
