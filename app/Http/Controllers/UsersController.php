<?php

namespace App\Http\Controllers;

use App\Models\{User};
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Notifications\UserInvitedNotification;

class UsersController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$users = User::query();

		if ($request->has('all') && $request->all == true) {
			$users->withTrashed();
		}

		if ($request->has('sort_by') && $sort_by = $request->sort_by) {
			$users->orderBy($sort_by, $request->direction ?? 'asc');
		}

		if ($request->has('filters')) {
			if ($request->has('filters.query') && $search_query = $request->filters['query']) {
				$users->where(function ($query) use ($search_query) {
					$query
						->where('first_name', 'like', "%{$search_query}%")
						->orWhere('last_name', 'like', "%{$search_query}%")
						->orWhere('email', 'like', "%{$search_query}%")
						->orWhere('phone', 'like', "%{$search_query}%")
						//
					;
				});
			}

			if ($request->has('filters.type') && $type = $request->filters['type']) {
				$users->where('type', $type);
			}

			if ($request->has('filters.status') && $status = $request->filters['status']) {
				switch ($status) {
					case 'active':
						$users->whereNull('deleted_at');
						break;
					case 'archived':
						$users->whereNotNull('deleted_at');
						break;
				}
			}

			if ($request->has('filters.date.from') &&  $start_date = $request->filters['date']['from']) {
				$users->whereDate('created_at', '>=', $start_date);
			}

			if ($request->has('filters.date.to') && $end_date = $request->filters['date']['to']) {
				$users->whereDate('created_at', '<=', Carbon::parse($end_date)->endOfDay());
			}

			if ($request->has('filters.exclude') && is_array($request->filters['exclude'])) {
				$users->whereNotIn('id', $request->filters['exclude']);
			}
		}

		return success([
			'users'			=> $users->paginate(get_page_limit(20)),
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$request->validate([
			'email'			=> 'required|email|max:255|unique:users,email',
			'phone'			=> 'max:255',
			'first_name'	=> 'nullable|max:255',
			'last_name'		=> 'nullable|max:255',
			'type'			=> 'required|in:' . implode(',', User::TYPES),
			'birth_date'	=> 'date|before:today',
		], [], ___('globals.user_fields'));

		$password = \Illuminate\Support\Str::random(8);

		/** @var User */
		$user = User::create([
			'email'					=> $request->email,
			'phone'					=> $request->phone ?? null,
			'first_name'			=> $request->first_name,
			'last_name'				=> $request->last_name,
			'type'					=> $request->type,
			'birth_date'			=> $request->birth_date ?? null,
			'language'				=> config('app.locale'),
			'password'				=> $password,
			'force_password_change'	=> true,
		]);

		try {
			$user->notify(new UserInvitedNotification($password));
		} catch (\Throwable $th) {
			return fail();
		}

		return success([
			'user' => $user,
		]);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(User $user)
	{
		return success([
			'user' => $user,
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, User $user)
	{
		$request->validate([
			'type'			=> 'required|in:' . implode(',', User::TYPES),
			'first_name'	=> 'required|max:255',
			'last_name'		=> 'required|max:255',
			'phone'			=> 'max:255',
			'birth_date'	=> 'date|before:today',
		], [], ___('globals.user_fields'));

		$user->update([
			'type'			=> $request->type,
			'first_name'	=> $request->first_name,
			'last_name'		=> $request->last_name,
			'phone'			=> $request->phone,
			'birth_date'	=> $request->birth_date,
		]);

		return success([
			'user' => $user,
		]);
	}

	public function update_status(Request $request, User $user)
	{
		$request->validate([
			'status'	=> 'required|in:active,archived',
		]);

		$user->update([
			'deleted_at' => $request->status == 'active' ? null : now(),
		]);

		return success([
			'user' => $user,
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(User $user)
	{
		//
	}
}
