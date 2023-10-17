<?php

namespace App\Http\Controllers;

use App\Models\{Localization};
use Illuminate\Http\Request;

class EntryController extends Controller
{
	public function index()
	{
		$blade_file = resource_path('views/index.blade.php');

		if (file_exists($blade_file)) {
			return view('index', [
				'locales'	=> json_encode(Localization::getUserLocales()),
			]);
		}

		return view('welcome');
	}
}
