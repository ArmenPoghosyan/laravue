import { computed } from 'vue';
import { locales } from './locales';

export const lists = {
	languages: [],

	prependAll(list, value = null) {
		return [{ label: locales.locale.t('globals.all'), value }, ...list];
	},

	appendAll(list, value = null) {
		return [...list, { label: locales.locale.t('globals.all'), value }];
	},
};
