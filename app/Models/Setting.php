<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Setting extends Model
{
	protected $fillable = [
		'parentable_type',
		'parentable_id',
		'key',
		'value',
	];

	/** @return MorphTo  */
	public function parentable()
	{
		return $this->morphTo();
	}
}
