<template>
	<ap-select v-model="value" :options="timezones" emit-value map-options />
</template>

<script>
import { ref, watch } from 'vue';

export default {
	emits: ['update:modelValue'],
	props: {
		modelValue: {
			type: String,
			default: null,
		},
		mask: {
			type: String,
			default: 'HH:mm',
		},
	},

	setup(props, { emit }) {
		const value = ref(props.modelValue);

		const timezones = [
			{ label: 'UTC +14', value: '+14:00' },
			{ label: 'UTC +13:45', value: '+13:45' },
			{ label: 'UTC +13', value: '+13:00' },
			{ label: 'UTC +12', value: '+12:00' },
			{ label: 'UTC +11', value: '+11:00' },
			{ label: 'UTC +10:30', value: '+10:30' },
			{ label: 'UTC +10', value: '+10:00' },
			{ label: 'UTC +9:30', value: '+9:30' },
			{ label: 'UTC +9', value: '+9:00' },
			{ label: 'UTC +8:45', value: '+8:45' },
			{ label: 'UTC +8', value: '+8:00' },
			{ label: 'UTC +7', value: '+7:00' },
			{ label: 'UTC +6:30', value: '+6:30' },
			{ label: 'UTC +6', value: '+6:00' },
			{ label: 'UTC +5:45', value: '+5:45' },
			{ label: 'UTC +5:30', value: '+5:30' },
			{ label: 'UTC +5', value: '+5:00' },
			{ label: 'UTC +4:30', value: '+4:30' },
			{ label: 'UTC +4', value: '+4:00' },
			{ label: 'UTC +3:30', value: '+3:30' },
			{ label: 'UTC +3', value: '+3:00' },
			{ label: 'UTC +2', value: '+2:00' },
			{ label: 'UTC +1', value: '+1:00' },
			{ label: 'UTC +0', value: '+0:00' },
			{ label: 'UTC -1', value: '-1:00' },
			{ label: 'UTC -2', value: '-2:00' },
			{ label: 'UTC -2:30', value: '-2:30' },
			{ label: 'UTC -3', value: '-3:00' },
			{ label: 'UTC -4', value: '-4:00' },
			{ label: 'UTC -5', value: '-5:00' },
			{ label: 'UTC -6', value: '-6:00' },
			{ label: 'UTC -7', value: '-7:00' },
			{ label: 'UTC -8', value: '-8:00' },
			{ label: 'UTC -9', value: '-9:00' },
			{ label: 'UTC -9:30', value: '-9:30' },
			{ label: 'UTC -10', value: '-10:00' },
			{ label: 'UTC -11', value: '-11:00' },
			{ label: 'UTC -12', value: '-12:00' },
		];

		watch(value, (newValue) => emit('update:modelValue', newValue));
		watch(props, (newProps) => (value.value = newProps.modelValue));

		return {
			timezones,
			value,
		};
	},
};
</script>
