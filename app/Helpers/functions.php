<?php

use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Models\{User, UserDevice};
use Illuminate\Http\Request;

#region User
/**
 * Get the user
 *
 * @param bool $with_all
 * @return null|User
 */
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

/**
 * Get the user's id
 *
 * @return null|int
 */
function user_id(): ?int
{
	return auth_user()?->id ?? null;
}

/**
 * Throw an unauthorized exception
 *
 * @return never
 * @throws UnauthorizedException
 */
function unauthorized()
{
	throw new \Illuminate\Validation\UnauthorizedException();
}
#endregion

#region Status
/**
 * Return a response with a status
 *
 * @param mixed $status
 * @param array $data
 * @param int $code
 * @return JsonResponse
 */
function status($status, $data = [], $code = 200)
{
	return new JsonResponse(
		['status' => $status] + $data,
		$code
	);
}

/**
 * Return a successful response
 *
 * @param array $data
 * @param int $code
 * @return JsonResponse
 */
function success($data = [], $code = 200)
{
	return status(true, $data, $code);
}

/**
 * Return a failed response
 *
 * @param array $data
 * @param int $code
 * @return JsonResponse
 */
function fail($data = [], $code = 200)
{
	return status(false, $data, $code);
}
#endregion

#region __Geo__

/**
 * Get User IP
 * @return mixed
 */
function user_ip()
{
	// Get real visitor IP behind CloudFlare network
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
		$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}

	// Sometimes the `HTTP_CLIENT_IP` can be used by proxy servers
	$ip = @$_SERVER['HTTP_CLIENT_IP'];
	if (filter_var($ip, FILTER_VALIDATE_IP)) {
		return $ip;
	}

	// Sometimes the `HTTP_X_FORWARDED_FOR` can contain more than IPs
	$forward_ips = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	if ($forward_ips) {
		$all_ips = explode(',', $forward_ips);

		foreach ($all_ips as $ip) {
			if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
				return $ip;
			}
		}
	}

	return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

/**
 * Get country by IP address
 *
 * @param mixed $ip
 * @return mixed
 */
function detect_country($ip = null)
{
	$response	= [];
	$ip			= $ip ?? user_ip();

	try {
		if (in_array($ip, ['127.0.0.1', '192.168.0.1'])) throw new Exception;
		$response = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip={$ip}"), true);
		if (!isset($response['geoplugin_countryCode'])) throw new Exception;

		return [
			'country_name'	=> $response['geoplugin_countryName'],
			'country_code'	=> $response['geoplugin_countryCode'],
		];

		return $response;
	} catch (\Exception $e) {
		return [
			'country_name'	=> null, //? config('app.fallback_country'),
			'country_code'	=> null, //? config('app.fallback_country_code'),
		];
	}
}
#endregion

/**
 * Get the page limit from the request\
 *
 * @param int $limit
 * @return mixed
 */
function get_page_limit($limit = 20)
{
	return request()->get('limit', $limit);
}

/**
 * Get localization
 *
 * @param mixed $path
 * @param array $replace
 * @param mixed $locale
 * @param mixed $fallback
 * @return mixed
 */
function ___($path, $replace = [], $locale = null, $fallback = null)
{
	$original_path = $path;

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
			return $fallback ?? $original_path;
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
function process_user_device(User $user = null, Request $request = null, bool $delete = false): null|bool|UserDevice
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

/**
 * Calculate discounted price
 *
 * @param mixed $price
 * @param mixed $discount
 * @return mixed
 */
function calculate_discounted_price($price, $discount)
{
	if ($discount > 0) {
		return (int)round($price - ($price * $discount / 100));
	}

	return $price;
}

/**
 * Round a price to 2 decimal places
 *
 * @param mixed $price
 * @return float
 */
function round_price($price, $decimals = 2)
{
	return round($price, $decimals);
}


/**
 * Call an event without throwing an exception
 *
 * @param mixed $args
 * @return void
 */
function safe_event(...$args)
{
	try {
		event(...$args);
	} catch (\Throwable $th) {
		report($th);
		//
	}
}

/**
 * Call a job without throwing an exception
 *
 * @param mixed $args
 * @return void
 */
function safe_dispatch(...$args)
{
	try {
		dispatch(...$args);
	} catch (\Throwable $th) {
		report($th);
	}
}
