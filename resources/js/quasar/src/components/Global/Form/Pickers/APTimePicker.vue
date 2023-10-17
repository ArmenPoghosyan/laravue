<template>
	<ap-input @click="open = true" v-model="value">
		<template #append>
			<q-icon name="access_time" class="cursor-pointer" />

			<q-popup-proxy v-model="open" cover transition-show="fade" transition-hide="fade" :breakpoint="600">
				<q-time v-model="value" :mask="mask">
					<div class="row items-center justify-end">
						<q-btn v-close-popup no-caps :label="$t('globals.apply')" color="primary" flat />
					</div>
				</q-time>
			</q-popup-proxy>
		</template>
	</ap-input>
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
		const open = ref(false);
		const value = ref(props.modelValue);

		watch(value, (newValue) => emit('update:modelValue', newValue));
		watch(props, (newProps) => (value.value = newProps.modelValue));

		return {
			open,
			value,
		};
	},
};
</script>
