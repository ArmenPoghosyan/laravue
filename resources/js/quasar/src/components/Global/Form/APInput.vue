<template>
	<q-input
		outlined
		dense
		:error="validation.error(validate)"
		:error-message="validation.errorMessage(validate)"
		:class="{
			'q-input--no-padding': noPadding,
			'q-input--no-padding-left': noPaddingLeft,
			'q-input--no-padding-right': noPaddingRight,
			'q-input--no-resize': noResize,
		}"
	>
		<slot />

		<template v-slot:prepend>
			<slot name="prepend" />
		</template>

		<template v-slot:append>
			<slot name="append" />
		</template>
	</q-input>
</template>

<script>
export default {
	props: {
		validate: { type: String, default: null },
		noPadding: { type: Boolean, default: false },
		noPaddingLeft: { type: Boolean, default: false },
		noPaddingRight: { type: Boolean, default: false },
		noResize: { type: Boolean, default: false },
	},
};
</script>

<style lang="scss">
.q-input {
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
