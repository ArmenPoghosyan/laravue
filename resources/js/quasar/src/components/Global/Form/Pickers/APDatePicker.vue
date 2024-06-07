<template>
	<ap-input v-model="formattedDate" @click.prevent="pickers.date = true">
		<template v-slot:append>
			<q-icon name="sym_o_calendar_month" class="cursor-pointer">
				<q-popup-proxy v-model="pickers.date" cover transition-show="fade" transition-hide="fade" :breakpoint="600">
					<ap-date :modelValue="range ? (value?.from == value?.to ? value?.from : value) : value" :range="range" :options="calendarOptions" @update:modelValue="dateUpdated">
						<div class="row items-center justify-end">
							<q-btn v-close-popup no-caps :label="$t('globals.apply')" color="primary" flat />
						</div>
					</ap-date>
				</q-popup-proxy>

				<q-popup-proxy v-model="pickers.time" cover transition-show="fade" transition-hide="fade" :breakpoint="600">
					<ap-time :modelValue="value?.slice(-8)" @update:modelValue="timeUpdated">
						<div class="row items-center justify-end">
							<q-btn v-close-popup no-caps :label="$t('globals.apply')" color="primary" flat />
						</div>
					</ap-time>
				</q-popup-proxy>
			</q-icon>
		</template>
	</ap-input>
</template>

<script>
import { computed, onUpdated, ref, watch } from 'vue';
import { core } from 'src/core';

import moment from 'moment';

export default {
	emits: ['update:modelValue'],
	props: {
		modelValue: {
			// type: String,
			// default: '',
		},

		niceDate: {
			type: Boolean,
			default: true,
		},

		minDate: {
			type: String,
			default: null,
		},

		maxDate: {
			type: String,
			default: null,
		},

		time: {
			type: Boolean,
			default: false,
		},

		range: {
			type: Boolean,
			default: false,
		},
	},

	setup(props, { emit }) {
		const value = ref(props.modelValue || (props.maxDate ? props.maxDate.replaceAll('/', '-') : null));

		const pickers = ref({
			date: false,
			time: false,
		});

		watch(value, function (newValue) {
			if (props.range) {
				if (typeof newValue == 'object') {
					emit('update:modelValue', newValue);
				}
			} else {
				emit('update:modelValue', newValue);
			}
		});

		onUpdated(() => {
			value.value = props.modelValue;
		});

		const formattedDate = computed({
			get() {
				if (props.range) {
					if (typeof value.value == 'object') {
						if (!value.value?.from && !value.value?.to) return '';
						return core.nice_date(value.value?.from) + ' - ' + core.nice_date(value.value?.to);
					}
				} else {
					if (!value.value) return '';

					return core.nice_date(props.modelValue, props.time);
				}

				return value.value;
			},

			set(new_value) {
				if (new_value) {
					value.value = new_value;
				} else {
					if (props.range) {
						value.value = {
							from: null,
							to: null,
						};
					} else {
						value.value = null;
					}
				}
			},
		});

		function calendarOptions(date) {
			let isAllowed = true;

			if (props.minDate) {
				if (props.minDate == 'today') {
					isAllowed = moment().format('YYYY/MM/DD') <= date;
				} else {
					isAllowed = props.minDate.replaceAll('-', '/') <= date;
				}
			}

			if (isAllowed && props.maxDate) {
				if (props.maxDate == 'today') {
					isAllowed = moment().format('YYYY/MM/DD') >= date;
				} else {
					isAllowed = props.maxDate.replaceAll('-', '/') >= date;
				}
			}

			return isAllowed;
		}

		function dateUpdated(_, reason, details) {
			if (props.range) {
				if (reason == 'remove-range') {
					reason = 'add-day';
				}

				let from = moment(value.value?.from || moment());
				let to = moment(value.value?.to || moment());

				switch (reason) {
					case 'add-day':
						from.set('date', details.day);
						from.set('month', details.month - 1);
						from.set('year', details.year);

						to.set('date', details.day);
						to.set('month', details.month - 1);
						to.set('year', details.year);
						break;

					case 'remove-day':
						from = null;
						to = null;
						break;

					case 'add-range':
						from.set('date', details.from.day);
						from.set('month', details.from.month - 1);
						from.set('year', details.from.year);
						to.set('date', details.to.day);
						to.set('month', details.to.month - 1);
						to.set('year', details.to.year);
						break;

					default:
						break;
				}

				value.value = {
					from: from ? from.format('YYYY-MM-DD') : null,
					to: to ? to.format('YYYY-MM-DD') : null,
				};

				// emit('update:modelValue', value.value);
			} else {
				const date = moment(value.value || moment());

				date.set('date', details.day);
				date.set('month', details.month - 1);
				date.set('year', details.year);

				if (props.time) {
					value.value = date.format('YYYY-MM-DD HH:mm:ss');
				} else {
					value.value = date.format('YYYY-MM-DD');
					// emit('update:modelValue', value.value);
				}

				if (props.time && (reason == 'add-day' || reason == 'remove-day')) {
					pickers.value.date = false;
					pickers.value.time = true;
				}
			}
		}

		function timeUpdated(_, details) {
			const date = moment(value.value || moment());

			date.set('hour', details.hour);
			date.set('minute', details.minute);
			date.set('seconds', '00');

			value.value = date.format('YYYY-MM-DD HH:mm:ss');
		}

		return {
			value,
			pickers,
			formattedDate,
			calendarOptions,

			dateUpdated,
			timeUpdated,
		};
	},
};
</script>
