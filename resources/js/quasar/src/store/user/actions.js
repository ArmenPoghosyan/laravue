import { api } from 'src/boot/axios';

const API = '/api';

export async function login({ commit, dispatch }, credentials) {
	const response = await api.post(`${API}/auth/login`, credentials);

	if (response.status) {
		commit('user/set', response.user, { root: true });
		commit('set_logged_in', true);

		dispatch('app/init', null, { root: true });
		dispatch('init_cookie_timer');
	}

	return response;
}

export async function forgot({ commit }, data) {
	return await api.post(`${API}/auth/forgot`, data);
}

export async function reset({ commit }, data) {
	return await api.post(`${API}/auth/reset`, data);
}

export async function logout({ commit, dispatch }) {
	const response = await api.post(`${API}/auth/logout`);

	if (response.status) {
		commit('clean');
		commit('set_logged_in', false);

		dispatch('destruct_cookie_timer');
		dispatch('app/init_cookie', null, { root: true });
	}

	return response;
}

export async function update({ commit, rootState }, data) {
	const response = await api.put(`${API}/user`, data);

	if (response.status) {
		commit('user/set', { ...rootState.user, ...response.user }, { root: true });
	}

	return response;
}

export async function check_password({ commit }, data) {
	return await api.post(`${API}/user/password/check`, data);
}

export async function change_password({ commit }, data) {
	return await api.put(`${API}/user/password`, data);
}

export async function change_email({ commit }, data) {
	return await api.put(`${API}/user/email`, data);
}

// system actions
export async function init_cookie_timer({ commit, dispatch }) {
	let cookie_timer = setInterval(() => {
		dispatch('app/init_cookie', null, { root: true });
	}, 2 * 59 * 59 * 1000); // Less than 2 hours

	commit('set', { cookie_timer });
}

export async function destruct_cookie_timer({ commit, state }) {
	clearInterval(state?.cookie_timer);
	commit('set', { cookie_timer: null });
}
