<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Builder};
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


	/**
	 * Get the value of a translation
	 *
	 * @param mixed $label
	 * @param mixed $class
	 * @param mixed $id
	 * @param string $language
	 * @return Builder
	 */
	public static function get_value($label, $class, $id = null, $language = 'en',)
	{
		$query = static::query()->select('value');

		if ($id) {
			if (strpos($id, '.') !== false) {
				$query->whereColumn('translatable_id', $id);
			} else {
				$query->where('translatable_id', $id);
			}
		}

		if ($class) {
			$query->where('translatable_type', $class);
		}

		if ($language) {
			$query->where('language', $language);
		}

		return $query->where('label', $label)->limit(1);
	}
}
