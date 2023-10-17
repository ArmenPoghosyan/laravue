<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDevice extends Model
{
	protected $fillable = [
		'user_id',
		'device_id',
		'device_name',
		'device_type',
		'push_token',
	];

	/** @return BelongsTo  */
	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
