<?php

namespace App\Traits;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property static $translatable
 * @property static $autoload_labels
 *
 * @method static $this order_translations(string $label, string $direction = 'asc')
 * @method $this order_translations(string $label, string $direction = 'asc')
 *
 * @method static $this load_labels(bool $bool) Load labels
 * @method $this load_labels(bool $bool) Load labels
 *
 * @method static $this load_translations(bool $bool) Load all translations
 * @method $this load_translations(bool $bool) Load all translations
 *
 * @method static $this search_translations(string $value, string $label = null, string $language = null, string $direction = 'asc')
 * @method $this search_translations(string $value, string $label = null, string $language = null, string $direction = 'asc')
 */
trait HasTranslations
{
	public static $autoload_labels = true;

	/** @return MorphMany  */
	public function translations()
	{
		return $this->morphMany(Translation::class, 'translatable');
	}

	/** @return void  */
	protected static function bootHasTranslations()
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
							'translatable_type'	=> $model->getMorphClass(),
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
								'translatable_type'	=> $model->getMorphClass(),
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
									'translatable_type'	=> $model->getMorphClass(),
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
					'translatable_type'	=> $model->getMorphClass(),
					'translatable_id'	=> $model->id
				])->delete();

				foreach (static::$translatable as $label) {
					Cache::forget(static::getCacheKey($label, $model->id));
				}
			}
		});

		if (!isset(static::$autoload_labels) || (isset(static::$autoload_labels) && static::$autoload_labels)) {
			static::addGlobalScope('translations', function ($builder) {
				$builder->load_labels();
			});
		}

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
		if ($model?->_load_translations || $model?->_load_labels) {
			if (static::$translatable && is_array(static::$translatable)) {
				$map		= [];
				$loadable	= [];

				foreach (static::$translatable as $label) {
					$cacheKey = static::getCacheKey($label, $model->id);

					if (Cache::has($cacheKey)) {
						$map[$label] = Cache::get($cacheKey);
					} else {
						$loadable[] = $label;
					}
				}


				if (count($loadable)) {
					$translations = $model->translations()
						->whereIn('label', $loadable)
						->where('translatable_id', $model->id)
						->get()
						//
					;

					$loaded = [];
					foreach ($translations as $translation) {
						$map[$translation->label][$translation->language] = $translation->value;
						$loaded[$translation->label] = true;
					}

					foreach (array_keys($loaded) as $label) {
						Cache::forever(static::getCacheKey($label, $model->id), $map[$label] ?? []);
					}
				}


				foreach ($map as $label => $translations) {
					if ($model?->_load_translations) {
						foreach ($translations as &$translation) {
							if (strlen($translation) == 0) {
								$translation = $translations[$model?->fallback_language ?? config('app.fallback_locale')] ?? '';
							}
						}

						$model->setAttribute($label, $translations);
					} else if ($model?->_load_labels) {
						$app_locale			= $model?->_language ?? app()->getLocale() ?? config('app.locale', 'en');
						$fallback_locale	= $model?->_fallback_language ?? config('app.fallback_locale', 'en');

						$model->setAttribute($label, $translations[$app_locale] ?? $translations[$fallback_locale] ?? '');
					}
				}
			}
		}

		$model->makeHidden([
			'_load_translations',
			'_load_labels',
			'_language',
			'_fallback_language',
		]);
	}

	/**
	 * Get cache key for specific field
	 *
	 * @param mixed $label
	 * @return string
	 */
	private static function getCacheKey($label, $id)
	{
		// return __CLASS__  . "\\Translations\\{$label}\\{$id}";
		return md5(__CLASS__  . "\\Translations\\{$label}\\{$id}");
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
			->where('translations.translatable_type', $this->getMorphClass())
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
			->where('translations.translatable_type', $this->getMorphClass())
			->where('translations.label', $label)
			->orderBy('translations.value', $direction)
			//
		;

		return $this;
	}

	/**
	 * Load translations
	 *
	 * @param mixed $query
	 * @param bool $bool
	 * @return void
	 */
	public function scopeLoad_translations($query, $bool = true)
	{
		$query->addSelect([
			'_load_translations' => fn ($q) => $q->selectRaw($bool ? 1 : 0)
		]);
	}

	/**
	 * Load only labels
	 *
	 * @param mixed $query
	 * @param bool $bool
	 * @return void
	 */
	public function scopeLoad_labels($query, $bool = true)
	{
		$query->addSelect([
			'_load_labels' => fn ($q) => $q->selectRaw($bool ? 1 : 0)
		]);
	}

	/**
	 * Set language for translations
	 *
	 * @param mixed $query
	 * @param string $language
	 * @return void
	 */
	public function scopeLanguage($query, $language = 'en')
	{
		$query->addSelect([
			'_language' => fn ($q) => $q->selectRaw("'{$language}'")
		]);
	}

	/**
	 * Set fallback language for translations
	 *
	 * @param mixed $query
	 * @param string $language
	 * @return void
	 */
	public function scopeFallback_language($query, $language = 'en')
	{
		$query->addSelect([
			'_fallback_language' => fn ($q) => $q->selectRaw("'{$language}'")
		]);
	}
}
