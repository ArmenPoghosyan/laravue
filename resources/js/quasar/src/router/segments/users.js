export default [
	{
		path: '/users',
		name: 'users.index',
		component: () => import('pages/Users/Index.vue'),
	},
	{
		path: '/user/:id',
		name: 'user.index',
		component: () => import('pages/Users/Single.vue'),
	},
];
