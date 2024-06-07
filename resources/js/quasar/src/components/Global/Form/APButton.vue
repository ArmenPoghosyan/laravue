<template>
	<q-btn rounded no-caps :label="hideLabel || (hideLabelSm && $q.screen.lt.sm) ? null : label">
		<slot />
	</q-btn>
</template>

<script>
export default {
	props: {
		label: {},
		hideLabel: {
			type: Boolean,
			default: false,
		},

		hideLabelSm: {
			type: Boolean,
			default: false,
		},
	},
};
</script>
