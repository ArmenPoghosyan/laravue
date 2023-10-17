const is_production = process.env.NODE_ENV === 'production';

const config = {
	app: {
		name: 'Project',
		home: 'home',
		languages: ['en', 'ru'],
		default_language: 'ru',
	},

	host: {
		development: 'http://localhost/',
		production: '/',
		current: null,
	},

	is_production,
};

config.host.current = is_production ? config.host.production : config.host.development;

export default config;
