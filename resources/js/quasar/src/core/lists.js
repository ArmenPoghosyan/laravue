import { computed } from 'vue';
import { locales } from './locales';

export const lists = {
	languages: [],

	user_types: computed(() => [
		{ label: locales.locale.t('globals.user_types.user'), value: 'user' },
		{ label: locales.locale.t('globals.user_types.manager'), value: 'manager' },
		{ label: locales.locale.t('globals.user_types.admin'), value: 'admin' },
	]),

	user_statuses: computed(() => [
		{ label: locales.locale.t('globals.active'), value: 'active' },
		{ label: locales.locale.t('globals.archived'), value: 'archived' },
	]),

	language_list: computed(() => {
		let languages = ['en'];

		return languages.map((language) => {
			return {
				label: locales.locale.t(`globals.language.${language}`),
				value: language,
			};
		});
	}),

	prependAll(list, value = null) {
		return [{ label: locales.locale.t('globals.all'), value }, ...list];
	},

	appendAll(list, value = null) {
		return [...list, { label: locales.locale.t('globals.all'), value }];
	},
};
