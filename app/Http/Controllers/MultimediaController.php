<?php

namespace App\Http\Controllers;

use App\Models\Multimedia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MultimediaController extends Controller
{
	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$request->validate([
			'type'	=> ['required', Rule::in(Multimedia::TYPES)],
		]);

		$multimedia = null;

		switch ($request->type) {
			case Multimedia::TYPE_PHOTO:
			case Multimedia::TYPE_VIDEO:
			case Multimedia::TYPE_DOCUMENT:
				$rules = [
					'file'	=> ['required', 'file', 'max:20480'],
				];

				if ($request->type === Multimedia::TYPE_PHOTO) {
					$max_width	= config('image.max_width', 2000);
					$max_height	= config('image.max_height', 2000);

					$rules['file'][] = 'mimes:jpg,png,bmp,gif';
					$rules['file'][] = "dimensions:max_width={$max_width},max_height={$max_height}";
				}

				$request->validate($rules, [], ___('globals.multimedia_types'));

				$multimedia = Multimedia::handle_file($request->file('file'), $request->type);

				break;

			case Multimedia::TYPE_LINK:
				$request->validate([
					'path' => 'required',
				]);

				$multimedia = new Multimedia([
					'type'	=> strtolower($request->type),
					'path'	=> $request->path,
				]);
				break;
		}

		if ($multimedia) {
			$multimedia->save();

			return success([
				'multimedia' => $multimedia,
			]);
		}

		return fail();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Multimedia $multimedia)
	{
		$multimedia->delete();
		return success();
	}
}
