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

if (is_production) {
	config.host.current = config.host.production;
} else {
	if (config.host.development) {
		config.host.current = config.host.development;
	} else {
		config.host.current = location.protocol + '//' + location.hostname;
	}
}

export default config;
