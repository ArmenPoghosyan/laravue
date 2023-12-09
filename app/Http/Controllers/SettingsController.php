<?php

namespace App\Http\Controllers;

use App\Models\{Setting, User};
use Illuminate\Http\Request;

class SettingsController extends Controller
{
	public function index(Request $request)
	{
		$request->validate([
			'parent'		=> 'required|string',
			'parent_id'		=> 'required|integer',
		]);

		$parent_id	= $request->parent_id;
		$parent		= match ($request->parent) {
			'user'		=> User::class,
			default		=> null
		};

		if (!$parent) return fail();

		$settings = Setting::query()
			->where('parentable_type', $parent)
			->where('parentable_id', $parent_id)
			->get()
			->keyBy('key')
			->map(function ($setting) {
				return $setting->value;
			})
			//
		;

		return success([
			'settings'	=> $settings,
		]);
	}

	public function update(Request $request)
	{
		$request->validate([
			'parent'		=> 'required|string',
			'parent_id'		=> 'required|integer',
			'settings'		=> 'required|array'
		]);

		$parent_id	= $request->parent_id;
		$settings	= $request->settings;
		$parent		= match ($request->parent) {
			'user'		=> User::class,
			default		=> null
		};

		if (!$parent) return fail();

		foreach ($settings as $key => $setting) {
			Setting::updateOrCreate(
				['parentable_type'	=> $parent, 'parentable_id' => $parent_id, 'key' => $key],
				['value'			=> $setting]
			);
		}

		return success();
	}
}
