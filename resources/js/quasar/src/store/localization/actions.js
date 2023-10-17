import { api } from 'src/boot/axios';

const API = '/api/localizations';

export async function index({ commit }) {
	return await api.get(`${API}`);
}

export async function show({ commit }, data) {
	return await api.get(`${API}/${data.id}`, data);
}

export async function store({ commit }, data) {
	return await api.post(`${API}`, data);
}

export async function update({ commit }, data) {
	return await api.put(`${API}/${data.id}`, data);
}

export async function destroy({ commit }, id) {
	return await api.delete(`${API}/${id}`);
}

export async function sync({ commit }) {
	return await api.post(`${API}/sync`);
}
