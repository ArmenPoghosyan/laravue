import config from 'app/project.config';
import { locales } from './locales';
import moment from 'moment/moment';
import { Store } from 'src/store';
import { core } from '.';
import { ref, watch } from 'vue';

export const misc = {
	nice_date(date, time = false, year = true, short = false) {
		let str_date = '';

		let timezone = Store.state?.app?.timezone;
		date = moment(date);

		if (timezone) {
			let offset = timezone.replace(/(\+|-)(\d):/, '$10$2:');
			date = date.utcOffset(offset);
		}

		if (date.isSame(moment(), 'day')) {
			str_date = locales.locale.t('globals.today');
		} else {
			let month = date.format('MMMM').toLowerCase();
			let bucket = short ? 'months_short' : 'months';
			month = locales.locale.t(`globals.calendar.${bucket}.${month}`);
			str_date = `${month} ${date.format(year ? 'DD, YYYY' : 'DD')}`;
		}

		if (time) {
			str_date += ', ' + date.format('HH:mm');
		}

		return str_date;
	},

	media(path, size = null) {
		let hostname = null;

		if (config.is_production) {
			hostname = location.protocol + '//' + location.hostname;
		} else {
			hostname = config.host.current;
		}

		return hostname + '/storage/media/' + (size ? size + '_' : '') + path;
	},

	media_url(id, size = 'l') {
		return `${config.host.current}media/${id}/${size}`;
	},

	open_media_popup(media) {
		Store.commit('app/media_popup', { open: true, media });
	},

	readable_seconds: (seconds) => {
		var levels = [
			[Math.floor(seconds / 31536000), core.locale.t('globals.time_formats.years')],
			[Math.floor((seconds % 31536000) / 86400), core.locale.t('globals.time_formats.days')],
			[Math.floor(((seconds % 31536000) % 86400) / 3600), core.locale.t('globals.time_formats.hours')],
			[Math.floor((((seconds % 31536000) % 86400) % 3600) / 60), core.locale.t('globals.time_formats.minutes')],
			[(((seconds % 31536000) % 86400) % 3600) % 60, core.locale.t('globals.time_formats.seconds')],
		];
		var text = '';

		for (var i = 0, max = levels.length; i < max; i++) {
			if (levels[i][0] === 0) continue;
			text += ' ' + levels[i][0] + ' ' + levels[i][1];
		}
		return text.trim();
	},

	to_form: (object) => {
		let form = new FormData();

		let r = (o, p) => {
			Object.keys(o).map((key) => {
				if (o[key] != null && typeof o[key] == 'object' && !(o[key] instanceof File)) {
					r(o[key], p ? (p += `[${key}]`) : key);
				} else {
					form.append(p ? `${p}[${key}]` : key, o[key]);
				}
			});
		};

		r(object, false);
		return form;
	},

	watcher(variable, props, emit) {
		const data = ref(props[variable] || null);

		watch(
			() => props[variable],
			(value) => {
				data.value = value;
			}
		);

		watch(
			() => data.value,
			(value) => {
				emit(`update:${variable}`, value);
			}
		);

		return data;
	},

	slugify_text: async (text, language = 'en') => {
		const response = await core.store.dispatch('localization/slugify', {
			text,
			language,
		});

		if (response.status) {
			return response?.text || '';
		}

		return '';
	},

	find_currency(id) {
		return core.store.state.app?.currencies?.find((currency) => currency.id === id)?.name ?? '';
	},
};
