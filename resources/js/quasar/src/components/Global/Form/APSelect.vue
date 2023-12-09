<template>
	<q-select outlined dense behavior="menu" :error="validation.error(validate)" :error-message="validation.errorMessage(validate)">
		<slot />

		<template v-slot:option="scope" v-if="availableSlots.includes('option')">
			<slot name="option" v-bind="scope" />
		</template>
	</q-select>
</template>

<script>
import { ref } from 'vue';

export default {
	props: {
		validate: { type: String, default: null },
		noPadding: { type: Boolean, default: false },
		noPaddingLeft: { type: Boolean, default: false },
		noPaddingRight: { type: Boolean, default: false },
		noResize: { type: Boolean, default: false },
	},

	setup(props, { slots }) {
		const availableSlots = ref(Object.keys(slots));

		return {
			availableSlots,
		};
	},
};
</script>

<style lang="scss">
.q-select {
	&--no-padding {
		$selector: '.q-field__control';

		#{$selector} {
			padding: 0 !important;
		}

		&-left {
			#{$selector} {
				padding-left: 0 !important;
			}
		}

		&-right {
			#{$selector} {
				padding-right: 0 !important;
			}
		}
	}

	&--no-resize {
		textarea {
			resize: none !important;
		}
	}
}
</style>
