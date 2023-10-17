<template>
	<q-date mask="YYYY-MM-DD" :locale="locales">
		<slot />
	</q-date>
</template>

<script>
import { core } from 'src/core';
export default {
	setup() {
		const locales = {
			days: [
				core.locale.t('globals.calendar.days.sunday'),
				core.locale.t('globals.calendar.days.monday'),
				core.locale.t('globals.calendar.days.tuesday'),
				core.locale.t('globals.calendar.days.wednesday'),
				core.locale.t('globals.calendar.days.thursday'),
				core.locale.t('globals.calendar.days.friday'),
				core.locale.t('globals.calendar.days.saturday'),
			],

			daysShort: [
				core.locale.t('globals.calendar.days_short.sunday'),
				core.locale.t('globals.calendar.days_short.monday'),
				core.locale.t('globals.calendar.days_short.tuesday'),
				core.locale.t('globals.calendar.days_short.wednesday'),
				core.locale.t('globals.calendar.days_short.thursday'),
				core.locale.t('globals.calendar.days_short.friday'),
				core.locale.t('globals.calendar.days_short.saturday'),
			],

			months: [
				core.locale.t('globals.calendar.months.january'),
				core.locale.t('globals.calendar.months.february'),
				core.locale.t('globals.calendar.months.march'),
				core.locale.t('globals.calendar.months.april'),
				core.locale.t('globals.calendar.months.may'),
				core.locale.t('globals.calendar.months.june'),
				core.locale.t('globals.calendar.months.july'),
				core.locale.t('globals.calendar.months.august'),
				core.locale.t('globals.calendar.months.september'),
				core.locale.t('globals.calendar.months.october'),
				core.locale.t('globals.calendar.months.november'),
				core.locale.t('globals.calendar.months.december'),
			],

			monthsShort: [
				core.locale.t('globals.calendar.months_short.january'),
				core.locale.t('globals.calendar.months_short.february'),
				core.locale.t('globals.calendar.months_short.march'),
				core.locale.t('globals.calendar.months_short.april'),
				core.locale.t('globals.calendar.months_short.may'),
				core.locale.t('globals.calendar.months_short.june'),
				core.locale.t('globals.calendar.months_short.july'),
				core.locale.t('globals.calendar.months_short.august'),
				core.locale.t('globals.calendar.months_short.september'),
				core.locale.t('globals.calendar.months_short.october'),
				core.locale.t('globals.calendar.months_short.november'),
				core.locale.t('globals.calendar.months_short.december'),
			],

			pluralDay: core.locale.t('globals.calendar.plural_day'),

			firstDayOfWeek: 1, // 0-6, 0 - Sunday, 1 Monday, ...
			format24h: true,
		};

		return {
			locales,
		};
	},
};
</script>
