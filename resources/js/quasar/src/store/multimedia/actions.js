import { api } from 'src/boot/axios';

const API = '/api/multimedia';

export async function store({ commit }, data) {
	const form = new FormData();

	if (data?.file) {
		form.append('file', data?.file);
	}

	if (data?.type) {
		form.append('type', data?.type);
	}

	if (data?.path) {
		form.append('path', data?.path);
	}

	return await api.postForm(`${API}`, form, data?.config || {});
}

export async function destroy({ commit }, id) {
	return await api.delete(`${API}/${id}`);
}
