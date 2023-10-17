<?php

namespace App\Traits;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property static $translatable
 *
 * @method static $this order_translations(string $label, string $direction = 'asc')
 * @method $this order_translations(string $label, string $direction = 'asc')
 *
 * @method static $this search_translations(string $value, string $label = null, string $language = null, string $direction = 'asc')
 * @method $this search_translations(string $value, string $label = null, string $language = null, string $direction = 'asc')
 */
trait HasTranslations
{
	/** @return MorphMany  */
	public function translations()
	{
		return $this->morphMany(Translation::class, 'translatable');
	}

	/** @return void  */
	protected static function booted()
	{
		if (!isset(static::$translatable)) {
			throw new \Exception('You must specify which fields will be translatable');
		}

		$translations = [];

		/**
		 * Store new translations into buffer for future processing
		 */
		static::creating(function (Model $model) use (&$translations) {
			if (static::$translatable && is_array(static::$translatable)) {
				foreach (static::$translatable as $label) {
					if (isset($model->attributes[$label])) {
						$translations[$label] = $model->attributes[$label];
						unset($model->attributes[$label]);
					}
				}
			}
		});

		/**
		 * Create the translations inserted in the buffer
		 */
		static::created(function (Model $model) use (&$translations) {
			foreach ($translations as $label => $values) {
				if (is_array($values)) {
					foreach ($values as $language => $value) {
						Translation::create([
							'label'				=> $label,
							'value'				=> $value ?? '',
							'language'			=> $language,
							'translatable_type'	=> $model::class,
							'translatable_id'	=> $model->id
						]);
					}

					Cache::forget(static::getCacheKey($label, $model->id));
					unset($translations[$label]);
				}
			}

			static::retrieve($model);
		});

		/**
		 * Retrieve existing translations
		 */
		static::retrieved(function (Model $model) {
			static::retrieve($model);
		});

		/**
		 * Update existing translations or create new ones
		 */
		static::updating(function (Model $model) {
			if (static::$translatable && is_array(static::$translatable)) {
				foreach (static::$translatable as $label) {
					if (isset($model->attributes[$label])) {
						$translations = $model->getAttribute($label);
						foreach ($translations as $language => $value) {
							$translation = Translation::where([
								'label'				=> $label,
								'language'			=> $language,
								'translatable_type'	=> $model::class,
								'translatable_id'	=> $model->id
							])->first();

							if ($translation) {
								if ($translation->value != $value) {
									$translation->update([
										'value'				=> $value,
									]);
								}
							} else {
								Translation::create([
									'label'				=> $label,
									'value'				=> $value,
									'language'			=> $language,
									'translatable_type'	=> $model::class,
									'translatable_id'	=> $model->id
								]);
							}
						}
					}

					Cache::forget(static::getCacheKey($label, $model->id));
					unset($model->attributes[$label]);
				}
			}
		});

		/**
		 * Set the translations after model has updated
		 */
		static::updated(function (Model $model) {
			static::retrieve($model);
		});

		/**
		 * Delete the translations before model has been deleted
		 */
		static::deleting(function (Model $model) {
			if (static::$translatable && is_array(static::$translatable)) {
				Translation::where([
					'translatable_type'	=> $model::class,
					'translatable_id'	=> $model->id
				])->delete();

				foreach (static::$translatable as $label) {
					Cache::forget(static::getCacheKey($label, $model->id));
				}
			}
		});

		foreach (static::$translatable as $translatable) {
			static::resolveRelationUsing($translatable, function ($model) use ($translatable) {
				return $model->translations()->where('label', $translatable);
			});
		}
	}

	/**
	 * Retrieve the translations
	 *
	 * @param mixed $model
	 * @return void
	 */
	private static function retrieve($model)
	{
		if (static::$translatable && is_array(static::$translatable)) {
			foreach (static::$translatable as $label) {
				$cacheKey = static::getCacheKey($label, $model->id);

				if (Cache::has($cacheKey)) {
					$translations = Cache::get($cacheKey);
				} else {
					$translations = $model->translations()
						->where('label', $label)
						->where('translatable_id', $model->id)
						->get()
						//
					;

					if ($translations->count()) {
						Cache::forever($cacheKey, $translations);
					}
				}

				$translations = $translations->pluck('value', 'language')->toArray();
				foreach ($translations as &$translation) {
					if (strlen($translation) == 0) {
						$translation = $translations[$model?->fallbackLanguage ?? config('app.fallback_locale')];
					}
				}

				$model->setAttribute($label, $translations);
			}
		}
	}

	/**
	 * Get cache key for specific field
	 *
	 * @param mixed $label
	 * @return string
	 */
	private static function getCacheKey($label, $id)
	{
		return __CLASS__  . "\\Translations\\{$label}\\{$id}";
	}

	/**
	 * Order by translation
	 *
	 * @param mixed $query
	 * @param mixed $label
	 * @param string $direction
	 * @return $this
	 */
	public function scopeSearch_translations($query, string $value, string $label = null, string $language = null, string $direction = 'asc')
	{
		$table	= $this->getTable();
		$key 	= $this->getKeyName() ?? 'id';

		$query
			->join('translations', 'translations.translatable_id', '=', "{$table}.{$key}")
			->where('translations.translatable_type', $this::class)
			->orderBy('translations.value', $direction)
			//
		;

		if (stripos($value, '%') === false) {
			$query->where('value', $value);
		} else {
			$query->where('value', 'LIKE', $value);
		}

		if ($label) {
			$query->where('label', $label);
		}

		if ($language) {
			$query->where('language', $language);
		}

		return $this;
	}

	/**
	 * Order by translation
	 *
	 * @param mixed $query
	 * @param mixed $label
	 * @param string $direction
	 * @return $this
	 */
	public function scopeOrder_translations($query, string $label, $direction = 'asc')
	{
		$table	= $this->getTable();
		$key	= $this->getKeyName() ?? 'id';

		$query
			->join('translations', 'translations.translatable_id', '=', "{$table}.{$key}")
			->where('translations.translatable_type', $this::class)
			->where('translations.label', $label)
			->orderBy('translations.value', $direction)
			//
		;

		return $this;
	}
}
