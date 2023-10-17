<?php

namespace App\Http\Controllers;

use App\Models\Localization;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LocalizationsController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$localizations = Localization::where('parent_id', null)
			->orderBy('type')
			->orderBy('key')
			->get()
			//
		;

		return success([
			'localizations' => $this->groupLocalizations($localizations)
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$request->validate([
			'parent_id'	=> 'nullable|exists:localizations,id',
			'type'		=> 'required|in:node,value',
			'key'		=> ['required', Rule::unique('localizations', 'key')->where('parent_id', $request->get('parent_id'))],
			'label'		=> 'nullable|string|max:255',
		]);

		switch ($request->type) {
			case Localization::TYPE_NODE:
				Localization::create([
					'parent_id'		=> $request->parent_id,
					'label'			=> $request->label,
					'key'			=> $request->key,
					'type'			=> Localization::TYPE_NODE,
					'language'		=> null,
					'value'			=> null,
				]);

				return success();

			case Localization::TYPE_VALUE:
				$values			= $request->value ?? [];
				foreach (config('app.locales') as $language) {
					Localization::create([
						'parent_id'		=> $request->parent_id,
						'label'			=> $request->label,
						'key'			=> $request->key,
						'type'			=> Localization::TYPE_VALUE,
						'language'		=> $language,
						'value'			=> $values[$language] ?? null,
					]);
				}

				return success();
		}

		return fail();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param \App\Models\Localization $localization
	 * @return \Illuminate\Http\Response
	 */
	public function show(Localization $localization, Request $request)
	{
		$localizations = $localization->children()->orderBy('type')->orderBy('key')->get();

		return success([
			'path'			=> $localization->getPath(),
			'localizations'	=> $this->groupLocalizations($localizations)
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Localization $localization
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Localization $localization)
	{
		$request->validate([
			'key'		=> ['required', Rule::unique('localizations', 'key')->where('parent_id', $request->get('parent_id'))->ignoreModel($localization, 'key')],
			'label'		=> 'nullable|string|max:255',
		]);

		switch ($request->type) {
			case Localization::TYPE_NODE:
				$localization->update([
					'label'			=> $request->label,
					'key'			=> $request->key,
				]);

				return success();

			case Localization::TYPE_VALUE:
				$values			= $request->value ?? [];

				foreach (config('app.locales') as $language) {
					Localization::updateOrCreate(
						[
							'parent_id' => $localization->parent_id,
							'key'		=> $localization->key,
							'type'		=> Localization::TYPE_VALUE,
							'language'	=> $language,
						],
						[
							'key'		=> $request->key,
							'label'		=> $request->label,
							'value'		=> $values[$language] ?? null,
						]
					);
				}

				return success();
		}

		return fail();

		$request->validate([
			'key' => [
				"required",
				Rule::unique('localizations', 'key')
					->where('parent_id', $request->get('parent_id'))
					->where('language', $localization->language)
					->ignoreModel($localization)
			]
		]);

		if ($localization->key != $request->key) {
			// Clear redis cache for correct navigation of child nodes
			$localization->emptyCache();
		}

		foreach ($request->only(['key', 'parent_id', 'type', 'label', 'value']) as $field => $value) {
			$localization->setAttribute($field, $value);
		}

		$localization->save();

		if ($localization->language == config('app.locale') && $localization->type == 'value') {
			$localization->translateOtherLanguages();
		}

		return success([
			'localization' => $localization
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param \App\Models\Localization $localization
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Localization $localization)
	{
		$localization->emptyCache();

		switch ($localization->type) {
			case Localization::TYPE_NODE:
				$localization->delete();
				break;

			case Localization::TYPE_VALUE:
				Localization::where('parent_id', $localization->parent_id)
					->where('key', $localization->key)
					->where('type', Localization::TYPE_VALUE)
					->delete()
					//
				;
				break;
		}

		return success();
	}

	/**
	 * Generate translation files
	 *
	 * @return JsonResponse
	 */
	public function sync()
	{
		// dispatch(new BuildLocalizations);

		try {
			Localization::buildLocalizations();
		} catch (\Exception $e) {
			return fail();
		}

		return success();
	}

	/**
	 * Get locales for web app
	 *
	 * @return JsonResponse
	 */
	public function app()
	{
		return success([
			'locales'	=> Localization::getUserLocales()
		]);
	}

	/**
	 * Change locale for web app
	 *
	 * @param Request $request
	 * @return mixed
	 */
	public function set_locale(Request $request)
	{
		$locale = $request->locale;

		if (in_array($locale, config('app.locales'))) {
			session()->put('locale', $locale);

			if ($user = auth_user()) {
				$user->update([
					'language'	=> $locale
				]);
			}

			return success();
		}

		return fail();
	}


	/**
	 * Group localizations with same key and different languages
	 *
	 * @param mixed $localizations
	 * @return array
	 */
	private function groupLocalizations($localizations)
	{
		$grouped = [];

		foreach ($localizations as $localization) {
			switch ($localization->type) {
				case Localization::TYPE_NODE:
					$grouped[$localization->key] = $localization->toArray();
					break;

				case Localization::TYPE_VALUE:
					if (isset($grouped[$localization->key])) {
						$grouped[$localization->key]['value'][$localization->language] = $localization->value;
					} else {
						$grouped[$localization->key] = $localization->toArray();
						$grouped[$localization->key]['value'] = [
							$localization->language => $localization->value
						];
					}
					break;
			}
		}

		return array_values($grouped);
	}
}
