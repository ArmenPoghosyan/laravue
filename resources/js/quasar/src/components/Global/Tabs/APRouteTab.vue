<template>
	<q-route-tab no-caps :class="{ 'q-tab--hide-label': hideLabel }">
		<slot />
	</q-route-tab>
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

<style lang="scss"></style>
