<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Route;

trait HasUser
{
	/** @return BelongsTo */
	public function user()
	{
		$relation = $this->belongsTo(User::class);

		if ($this->has_user_with_trashed()) {
			$relation->withTrashed();
		}

		//* If you want to include trashed users in the relationship
		// if ($route = Route::current()) {
		// 	if (in_array('api', $route->middleware())) {
		// 		$relation->withTrashed();
		// 	}
		// }

		return $relation;
	}

	/**
	 * Get the user's full name.
	 *
	 * @param mixed $query
	 * @return void
	 */
	public function scopeUser_full_name($query)
	{
		$user_query = User::selectRaw('CONCAT_WS(" ", first_name, last_name)')
			->whereColumn('users.id', 'user_id')
			->limit(1)
			//
		;

		if ($this->has_user_with_trashed()) {
			$user_query->withTrashed();
		}

		$query->addSelect([
			'user_full_name' => $user_query
			//
		]);
	}

	/**
	 * Check if the user relationship should include trashed users.
	 *
	 * @return bool
	 */
	private function has_user_with_trashed()
	{
		return isset($this->with_trashed_users) && $this->with_trashed_users;
	}
}
