<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class Paginator extends LengthAwarePaginator
{
	public function toArray()
	{
		return [
			'data'			=> $this->items->toArray(),
			'total'			=> $this->total(),
			'count'			=> $this->count(),
			'per_page'		=> $this->perPage(),
			'page'			=> $this->currentPage(),
			'total_pages'	=> $this->lastPage(),
		];
	}
}
