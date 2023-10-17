import { boot } from 'quasar/wrappers';
import { createI18n } from 'vue-i18n';
import { api } from './axios';
import config from 'app/project.config';
import { lists } from 'src/core/lists';

let default_locale = config.app.default_language;
let storage_locale = localStorage.getItem('current_locale');

if (storage_locale && config.app.languages.indexOf(storage_locale) > -1) {
	default_locale = storage_locale;
} else {
	localStorage.setItem('current_locale', default_locale);
}

//? On Production we're loading translations through __locales variable
//? Which is rendered and passed to front through index.blade.php file

//? On Development we will send GET request to get all locales
//? We're using GET for caching purposed only.

const i18n = createI18n({
	locale: default_locale,
	fallbackLocale: 'en',
	messages: window.__locales ?? [],
	warnHtmlInMessage: 'off',
	warnHtmlMessage: false,

	globalInjection: true,

	silentFallbackWarn: true, // config.is_production,
	silentTranslationWarn: true, //config.is_production,
});

export { i18n };

export default boot(async ({ app }) => {
	if (!config.is_production) {
		let messages = await api.getCached('api/app/localizations');

		if (messages?.status) {
			Object.keys(messages.locales).map((language) => {
				i18n.global.setLocaleMessage(language, messages.locales[language] ?? []);
			});
		}
	}

	app.use(i18n);

	lists.languages = config.app.languages.map((language) => {
		return {
			value: language,
			label: i18n.global.t(`globals.languages.${language}`),
		};
	});

	app.config.globalProperties.current_locale = default_locale;
});
