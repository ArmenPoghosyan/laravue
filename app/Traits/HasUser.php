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

		//* If you want to include trashed users in the relationship
		// if ($route = Route::current()) {
		// 	if (in_array('api', $route->middleware())) {
		// 		$relation->withTrashed();
		// 	}
		// }

		return $relation;
	}
}
