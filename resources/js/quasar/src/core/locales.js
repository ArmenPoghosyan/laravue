import { i18n } from 'src/boot/i18n';
import { Store } from 'src/store';
import config from 'app/project.config';
import { lists } from './lists';

export const locales = {
	locale: i18n.global,
	current_language: i18n.global.locale,

	spreadLanguages(value = '', languages = null) {
		let object = {};

		if (languages) {
			languages.forEach((language) => {
				object[language] = value;
			});
		} else {
			lists.language_list.value.forEach((language) => {
				object[language.value] = value;
			});
		}

		return object;
	},

	async set_locale(locale) {
		if (config.app.languages.indexOf(locale) > -1) {
			localStorage.setItem('current_locale', locale);

			await Store.dispatch('app/set_locale', locale);
			window.location.reload();
		}
	},

	label(key, replace = {}) {
		return i18n.global.t(key, replace);
	},
};
