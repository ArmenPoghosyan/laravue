import config from 'app/project.config';
import { locales } from './locales';
import moment from 'moment/moment';
import { Store } from 'src/store';
import { core } from '.';

export const misc = {
	nice_date(date, time = false, year = true, short = false) {
		let str_date = '';

		date = moment(date);

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
};
