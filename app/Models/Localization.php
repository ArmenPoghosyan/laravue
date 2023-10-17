<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Support\Facades\Cache;

class Localization extends Model
{
	const TYPE_NODE		= 'node';
	const TYPE_VALUE	= 'value';

	const USER_IGNORE_NODES = [
		'emails.*',
	];

	protected $fillable = [
		'parent_id',
		'type',
		'label',
		'key',
		'language',
		'value'
	];

	/** @return BelongsTo */
	public function parent()
	{
		return $this->belongsTo(Localization::class, 'parent_id');
	}

	/** @return HasMany */
	public function children()
	{
		return $this->hasMany(Localization::class, 'parent_id')->orderBy('type');
	}

	/**
	 * Get Path
	 *
	 * @return array
	 */
	public function getPath(): array
	{
		$parents = Cache::get('localization:parents:' . $this->id);
		if ($parents) {
			return json_decode($parents, true);
		} else {
			$parents	= [];
			$parent		= $this->parent;

			while ($parent) {
				$parents[] = $parent->only('id', 'key');
				$parent = $parent->parent;
			}

			if ($parents) {
				$parents = array_reverse($parents);
			}

			$parents[] = [
				'id'	=> $this->id,
				'key'	=> $this->key
			];

			Cache::forever('localization:parents:' . $this->id, json_encode($parents));
		}

		return $parents;
	}

	/**
	 * Remove Redis cache
	 *
	 * @return void
	 */
	public function emptyCache()
	{
		$children = $this->children;

		foreach ($children as $child) {
			$child->emptyCache();
		}

		Cache::forget('localization:parents:' . $this->id);
	}

	/**
	 * Build Tree
	 *
	 * @return array
	 */
	public static function buildLocalizations()
	{
		set_time_limit(0);
		ini_set('memory_limit', '-1');

		$getNodes = function ($language, $parent_id = null, $nodes = []) use (&$getNodes) {
			$items = static::where('parent_id', $parent_id)->get();
			foreach ($items as $item) {
				if ($item->children->count() && $item->type == 'node') {
					$nodes[trim($item->key)] = $getNodes($language, $item->id, []);
				} elseif ($item->language == $language) {
					$nodes[trim($item->key)] = $item->value;
				}
			}

			return $nodes;
		};

		foreach (config('app.locales') as $language) {
			$path = app()->langPath("{$language}.json");
			if (!is_dir($dir = dirname($path))) {
				@mkdir($dir, 0755, true);
			}

			$nodes = $getNodes($language);
			file_put_contents($path, json_encode($nodes));

			if (isset($nodes['validation'])) {
				@mkdir(app()->langPath("{$language}"), 0755, true);
				file_put_contents(app()->langPath("{$language}/validation.php"), "<?php\n\nreturn " . var_export($nodes['validation'], true) . ";\n");
			}
		}

		@copy(
			app()->resourcePath('lang/validation.php'),
			app()->langPath('en/validation.php'),
		);
	}

	/**
	 * Get cached locales of the specified language
	 *
	 * @param mixed $locale
	 * @return mixed
	 */
	public static function getCached($locale, $user_locales = false)
	{
		$path = app()->langPath("{$locale}.json");

		if (file_exists($path)) {
			$modifiedTime = filemtime($path);
			$key = "cached_locales_{$locale}_{$modifiedTime}.json";

			if (Cache::has($key)) {
				$locales = Cache::get($key);
			} else {
				$locales = json_decode(file_get_contents($path), true);

				if ($user_locales && count(static::USER_IGNORE_NODES)) {
					$fn = function (&$array, $path = null) use (&$fn) {
						foreach ($array as $key => &$value) {
							$xPath = $path ? $path . '.' . $key : $key;

							if (is_array($value)) {
								$fn($value, $xPath);
							}

							foreach (static::USER_IGNORE_NODES as $pattern) {
								if (preg_match_all("/{$pattern}/i", $xPath)) {
									unset($array[$key]);
								}
							}
						}
					};

					$fn($locales);
				}

				Cache::forever($key, $locales);
			}

			return $locales;
		}

		return [];
	}

	/**
	 * Get All locales of all languages
	 *
	 * @return mixed
	 */
	public static function getAllLocales()
	{
		return array_combine(config('app.languages'), array_map(function ($locale) {
			return Localization::getCached($locale);
		}, config('app.languages')));
	}

	/**
	 * Get User languages
	 *
	 * @return array
	 */
	public static function getUserLocales()
	{
		$default_locale	= config('app.locale');
		$user_locale	= auth_user()?->language ?? session()->get('locale', $default_locale);

		$locales	= [
			$user_locale => static::getCached($user_locale, user_locales: true)
		];

		if ($user_locale != $default_locale) {
			$locales[$default_locale] = static::getCached($default_locale);
		}

		return $locales;
	}

	/**
	 * Translate other languages
	 *
	 * @return void
	 */
	public function translateOtherLanguages()
	{
		// if (!$this->value) return;
		// if (!$this->type == 'value') return;
		// foreach (config('app.locales') as $language) {
		// 	if ($language == $this->language) continue;

		// 	$data = [
		// 		'parent_id'	=> $this->parent_id,
		// 		'key'		=> $this->key,
		// 		'label'		=> $this->label,
		// 		'language'	=> $language,
		// 	];

		// 	$translated = Translator::translate($this->value, $this->language, $language, 'html')?->translatedText;

		// 	if ($translated !== null) {
		// 		if ($localization = Localization::where($data)->first()) {
		// 			if ($localization->value) continue;
		// 			$localization->update(['value' => $translated]);
		// 		} else {
		// 			$data['value'] = $translated;
		// 			Localization::create($data);
		// 		}
		// 	}
		// }
	}

	/**
	 * Use this method to restore localizations from files
	 * Ensure that you know what you are doing before using this method
	 *
	 * @return void
	 */
	public static function restore_from_files()
	{
		$locales_ru = json_decode(@file_get_contents(lang_path('ru.json') ?? []), true);
		$locales_en = json_decode(@file_get_contents(lang_path('en.json') ?? []), true);

		$fn = function ($arr_en, $arr_ru, $parent_id = null) use (&$fn) {
			foreach ($arr_en as $key => $item) {
				if (is_array($item)) {

					$parent = Localization::create([
						'parent_id'	=> $parent_id,
						'key'		=> $key,
						'type'		=> Localization::TYPE_NODE,
					]);

					$fn($item, $arr_ru[$key] ?? [], $parent->id);
				} else {
					if (isset($arr_en[$key])) {
						Localization::create([
							'parent_id'	=> $parent_id,
							'key'		=> $key,
							'type'		=> Localization::TYPE_VALUE,
							'language'	=> 'en',
							'value'		=> $arr_en[$key],
						]);
					}

					if (isset($arr_ru[$key])) {
						Localization::create([
							'parent_id'	=> $parent_id,
							'key'		=> $key,
							'type'		=> Localization::TYPE_VALUE,
							'language'	=> 'ru',
							'value'		=> $arr_ru[$key],
						]);
					}
				}
			}
		};

		$fn($locales_en, $locales_ru, null);
	}
}
