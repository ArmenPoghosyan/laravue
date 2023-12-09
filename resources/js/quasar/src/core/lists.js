import { computed } from 'vue';
import { locales } from './locales';

export const lists = {
	languages: [],

	user_types: computed(() => [
		{ label: locales.locale.t('globals.user_types.user'), value: 'user' },
		{ label: locales.locale.t('globals.user_types.manager'), value: 'manager' },
		{ label: locales.locale.t('globals.user_types.admin'), value: 'admin' },
	]),

	prependAll(list, value = null) {
		return [{ label: locales.locale.t('globals.all'), value }, ...list];
	},

	appendAll(list, value = null) {
		return [...list, { label: locales.locale.t('globals.all'), value }];
	},
};
