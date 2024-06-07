<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Traits\{HasLocalScopes, HasNotifications, HasSettings};
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\{MustVerifyEmail};
use Illuminate\Database\Eloquent\Relations\{HasMany};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use Mockery\Matcher\Type;

class User extends Authenticatable implements MustVerifyEmail
{
	use
		SoftDeletes,
		HasApiTokens,
		CanResetPassword,
		//
		HasFactory,
		HasLocalScopes,
		HasSettings,
		HasNotifications
		//
	;

	const TYPE_USER		= 'user';
	const TYPE_MANAGER	= 'manager';
	const TYPE_ADMIN	= 'admin';

	const TYPES = [
		self::TYPE_USER,
		self::TYPE_MANAGER,
		self::TYPE_ADMIN,
	];

	protected $fillable = [
		'first_name',
		'last_name',
		'type',
		'email',
		'phone',
		'avatar',
		'birth_date',
		'password',
		'force_password_change',
		'language',
		'email_verified_at',
		'deleted_at'
	];

	protected $hidden = [
		'password',
		'remember_token',
		'deleted_at',
		'updated_at',
		'email_verified_at'
	];

	protected $casts = [
		'birth_date'			=> 'string',
		'email_verified_at'		=> 'datetime',
		'password'				=> 'hashed',
		'force_password_change'	=> 'boolean',
	];

	protected $appends = [
		'age',
		'full_name',
		'status'
	];

	/** @return HasMany  */
	public function devices()
	{
		return $this->hasMany(UserDevice::class);
	}

	/**
	 * Get the user's full name
	 *
	 * @return string
	 */
	public function getFullNameAttribute()
	{
		return $this->first_name . ' ' . $this->last_name;
	}

	/**
	 * Get the user's age
	 *
	 * @return int
	 */
	public function getAgeAttribute()
	{
		if ($this->birth_date) {
			return Carbon::parse($this->birth_date)->age ?? 0;
		}

		return 0;
	}

	/**
	 * Get the user's language
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function getLanguageAttribute($value)
	{
		return $value ?? config('app.locale');
	}

	/**
	 * Hash the password before saving
	 *
	 * @param mixed $value
	 * @return void
	 */
	public function setPasswordAttribute($value)
	{
		$this->attributes['password'] = Hash::make($value);
	}

	/**
	 * Get the user status
	 *
	 * @return string
	 */
	public function getStatusAttribute()
	{
		if ($this->deleted_at) {
			return 'archived';
		}

		return 'active';
	}

	/**
	 * Send the password reset notification
	 *
	 * @param string $token
	 * @return void
	 */
	public function sendPasswordResetNotification($token)
	{
		$this->notify(new ResetPasswordNotification($token));
	}

	/**
	 * Check if the password is correct
	 *
	 * @param mixed $password
	 * @return bool
	 */
	public function check_password($password)
	{
		return Hash::check($password, $this->password);
	}

	/**
	 * Get all user tokens for push notifications
	 *
	 * @return mixed
	 */
	public function routeNotificationForFcm()
	{
		return $this->devices()->pluck('push_token')->toArray();
	}
}
