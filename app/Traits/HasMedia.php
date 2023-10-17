<?php

namespace App\Traits;

use App\Models\Multimedia;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasMedia
{
	/** @return MorphMany  */
	public function media()
	{
		return $this->morphMany(Multimedia::class, 'parentable')->orderBy('order');
	}

	/**
	 * Attach media to model
	 *
	 * @param array $media
	 * @return void
	 */
	public function attach_media(array $media)
	{
		foreach ($media as $id) {
			Multimedia::where('id', $id)
				->update([
					'parentable_id'		=> $this->id,
					'parentable_type'	=> $this::class
				])
				//
			;
		}
	}

	/**
	 * Attach media to model and set the ordering
	 *
	 * @param array $media
	 * @return void
	 */
	public function attach_ordered_media(array $media)
	{
		foreach ($media as $id => $order) {
			Multimedia::where('id', $id)
				->update([
					'order'				=> $order,
					'parentable_id'		=> $this->id,
					'parentable_type'	=> $this::class
				])
				//
			;
		}
	}
}
