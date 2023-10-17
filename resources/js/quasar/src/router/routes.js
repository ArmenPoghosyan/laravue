import localization from './segments/localizations';

const routes = [
	{
		path: '/',
		meta: { auth: true },
		component: () => import('layouts/MainLayout.vue'),
		children: [
			{
				path: '/',
				name: 'home',
				component: () => import('pages/HomePage.vue'),
			},
			...localization,
		],
	},

	// Auth routes
	{
		path: '/',
		meta: { is_auth: true },
		component: () => import('layouts/AuthLayout.vue'),
		children: [
			{
				path: 'login',
				name: 'auth.login',
				component: () => import('pages/Auth/Login.vue'),
			},
			{
				path: 'forgot',
				name: 'auth.forgot',
				component: () => import('pages/Auth/ForgotPassword.vue'),
			},
			{
				path: 'reset/:token',
				name: 'auth.reset',
				component: () => import('pages/Auth/ResetPassword.vue'),
			},
		],
	},

	// Always leave this as last one,
	// but you can also remove it
	{
		path: '/:catchAll(.*)*',
		name: '404',
		component: () => import('pages/ErrorNotFound.vue'),
	},
];

export default routes;
