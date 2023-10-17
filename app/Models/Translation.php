<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Translation extends Model
{
	protected $hidden	= ['created_at', 'updated_at'];
	protected $guarded	= [];

	/** @return MorphTo  */
	public function translatable()
	{
		return $this->morphTo();
	}

	/**
	 * Search for a translation
	 *
	 * @param mixed $query
	 * @param mixed $search
	 * @param mixed $language
	 * @return void
	 */
	public function scopeSearch($query, $search, $language = null)
	{
		if ($language) {
			$query->where('language', $language);
		}

		$query->where('value', 'LIKE', "%{$search}%");
	}
}
