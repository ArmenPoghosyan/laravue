import { boot } from 'quasar/wrappers';
import { validation } from 'src/core/validation';
import { core } from 'src/core';
import config from 'app/project.config';

export default boot(({ app, store }) => {
	core.$q = { ...app.config.globalProperties.$q };

	app.config.globalProperties.validation = validation;

	app.config.globalProperties.lists = core.lists;
	app.config.globalProperties.config = config;
	app.config.globalProperties.store = store;
	app.config.globalProperties.state = store.state;
	app.config.globalProperties.$user = store.state.user;

	app.config.globalProperties.logout = core.user.logout;
	app.config.globalProperties.set_locale = core.set_locale;
	app.config.globalProperties.nice_date = core.nice_date;
	app.config.globalProperties.readable_seconds = core.readable_seconds;
	app.config.globalProperties.media = core.media;
	app.config.globalProperties.open_media_popup = core.open_media_popup;
});
