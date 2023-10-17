export default function () {
	return {
		is_logged_in: false,
		cookie_timer: null,

		id: null,
		company_id: null,
		first_name: null,
		last_name: null,
		nick_name: null,
		full_name: null,
		type: null,
		email: null,
		phone: null,
		avatar: null,
		birth_date: null,
		force_change_password: null,
		language: null,

		settings: {},
	};
}
