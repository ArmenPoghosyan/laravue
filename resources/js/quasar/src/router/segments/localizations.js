export default [
	{
		path: '/localizations/:id?',
		name: 'localizations.index',
		component: () => import('pages/Localizations/Index.vue'),
	},
];
