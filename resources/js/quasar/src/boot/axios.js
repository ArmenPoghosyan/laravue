import { boot } from 'quasar/wrappers';
import axios from 'axios';
import config from 'app/project.config';

const axiosInstance = axios.create({
	baseURL: config.host.current,
	withCredentials: true,
});

async function sendRequest(url, params, method = 'GET', isForm = false, config = {}) {
	try {
		let headers = {
			'Content-Type': 'application/json',
			// 'X-Socket-Id': core.echo.socketId(),
		};
		if (isForm) {
			headers['Content-Type'] = 'multipart/form-data';
		}

		let response = null;
		switch (method) {
			case 'GET_CACHED':
				response = await axiosInstance.get(url, { params: { ...params } }, { headers, ...config });
				break;

			case 'GET':
				response = await axiosInstance.get(url, { params: { ...params, _t: Math.floor(Date.now() / 1000) } }, { headers, ...config });
				break;
			case 'POST':
				response = await axiosInstance.post(url, params, { headers, ...config });
				break;
			case 'PUT':
			case 'PATCH':
			case 'DELETE':
				let data;
				if (isForm) {
					params.append('_method', method);
					data = params;
				} else {
					data = { ...params, _method: method };
				}
				response = await axiosInstance.post(url, data, { headers, ...config });
				break;
		}

		if (response) {
			return {
				...response.data,
				statusCode: response.status,
			};
		}

		return {
			statusCode: 404,
		};
	} catch (error) {
		// * If you need to force logout when unauthorized
		// if (error.response.status === 401) {
		// 	setTimeout(() => {
		// 		if (!core.router.currentRoute.value.meta?.is_auth && !core.router.currentRoute.value.meta?.is_public) {
		// 			let route = core.router.resolve({ name: 'auth.login' });
		// 			let path = route?.path || '/login';
		// 			if (location.pathname != path) {
		// 				location.pathname = path;
		// 			}
		// 		}
		// 	}, 1000);
		// }

		return {
			status: false,
			...error.response?.data,
			statusCode: error.response?.status,
		};
	}
}

const api = {
	async get(url, params, config = {}) {
		return sendRequest(url, params, 'GET', false, config);
	},

	async getCached(url, params, config = {}) {
		return sendRequest(url, params, 'GET_CACHED', false, config);
	},

	async post(url, params, config = {}) {
		return sendRequest(url, params, 'POST', false, config);
	},

	async put(url, params, config = {}) {
		return sendRequest(url, params, 'PUT', false, config);
	},

	async patch(url, params, config = {}) {
		return sendRequest(url, params, 'PATCH', false, config);
	},

	async delete(url, params, config = {}) {
		return sendRequest(url, params, 'DELETE', false, config);
	},

	async postForm(url, params, config = {}) {
		return sendRequest(url, params, 'POST', true, config);
	},

	async putForm(url, params, config = {}) {
		return sendRequest(url, params, 'PUT', true, config);
	},

	async patchForm(url, params, config = {}) {
		return sendRequest(url, params, 'PATCH', true, config);
	},
};

export { api };

export default boot(({ app }) => {
	// Uncomment this if you want to use axios in your components
	// app.config.globalProperties.$axios = axios;
	// app.config.globalProperties.$api = api;
});
