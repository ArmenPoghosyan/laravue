<template>
	<q-tab no-caps :class="{ 'q-tab--hide-label': hideLabel }">
		<slot />
	</q-tab>
</template>

<script>
export default {
	props: {
		hideLabel: {
			type: Boolean,
		},
	},
};
</script>

<style lang="scss">
.q-tab {
	&--hide-label {
		.q-tab__label {
			display: none;
		}
	}
}
</style>
