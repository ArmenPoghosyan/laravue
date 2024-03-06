<?php

namespace App\Models;

use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Model;

/**
 * Needs for push notifications
 *
 * @package App\Models
 */
class UserDevice extends Model
{
	use HasUser;

	protected $fillable = [
		'user_id',
		'device_id',
		'device_name',
		'device_type',
		'push_token',
	];
}
