import { api } from 'src/boot/axios';

const API = '/api/users';

export async function index({ commit }, data) {
	return await api.post(`${API}`, data);
}

export async function show({ commit }, data) {
	let id = data.id || null;
	delete data.id;
	return await api.get(`${API}/${id}`, data);
}

export async function store({ commit }, data) {
	return await api.post(`${API}/create`, data);
}

export async function update({ commit }, data) {
	return await api.put(`${API}/${data.id}`, data);
}

export async function update_status({ commit }, data) {
	return await api.put(`${API}/${data.id}/status`, data);
}

export async function destroy({ commit }, id) {
	return await api.delete(`${API}/${id}`);
}
