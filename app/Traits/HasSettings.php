<?php

namespace App\Traits;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasSettings
{
	/**
	 * @return MorphMany
	 */
	public function settings()
	{
		return $this->morphMany(Setting::class, 'parentable');
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return Setting
	 */
	public function set_setting(string $key, $value)
	{
		return $this->settings()->updateOrCreate(['key' => $key], ['value' => $value]);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get_setting(string $key, $default = null)
	{
		$setting = $this->settings()->where('key', $key)->first();

		return $setting ? $setting->value : $default;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function has_setting(string $key)
	{
		return $this->settings()->where('key', $key)->exists();
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function remove_setting(string $key)
	{
		return $this->settings()->where('key', $key)->delete();
	}

	/**
	 * @param array $settings
	 * @return void
	 */
	public function set_settings(array $settings)
	{
		foreach ($settings as $key => $value) {
			$this->set_setting($key, $value);
		}
	}

	/**
	 * @param array $keys
	 * @return void
	 */
	public function remove_settings(array $keys)
	{
		foreach ($keys as $key) {
			$this->remove_setting($key);
		}
	}

	/**
	 * Remove all settings
	 *
	 * @return bool
	 */
	public function clear_settings()
	{
		$this->settings()->delete();
		return true;
	}
}
