import { api } from 'src/boot/axios';

const API = '/api';

export async function init({ commit, dispatch }) {
	await dispatch('init_cookie');
	const response = await api.post(`${API}/auth/init`);

	if (response.status) {
		await commit('user/set', { ...response?.user, is_logged_in: true }, { root: true });
		await dispatch('user/init_cookie_timer', null, { root: true });
	}

	commit('set_loaded', true);

	return response;
}

export async function init_cookie({ commit }) {
	await api.get(`${API}/sanctum/csrf-cookie`);
}

export async function set_locale({ commit }, locale) {
	await api.post(`${API}/app/set_locale`, { locale });
}

export async function update_settings({ commit, rootState }, data) {
	const response = await api.put(`${API}/settings`, data);

	if (response.status) {
		// * Custom logic
	}

	return response;
}
