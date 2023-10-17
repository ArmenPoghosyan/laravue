<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder scopes(...$scopes)
 * @method Builder scopes(...$scopes)
 */
trait HasLocalScopes
{
	/**
	 * Apply the scopes to the Eloquent builder instance and return it.
	 *
	 * @param mixed $query
	 * @param mixed $scopes
	 * @return Builder
	 */
	public function scopeScopes($query, $scopes)
	{
		if (count($scopes) === 0) return $query;

		foreach ($scopes as $scope) {
			$query->$scope();
		}

		return $query;
	}
}
