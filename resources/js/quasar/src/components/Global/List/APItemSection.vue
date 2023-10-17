<template>
	<q-item-section :class="{ 'q-item-section--icon': icon }">
		<slot />
	</q-item-section>
</template>

<script>
export default {
	props: {
		icon: {
			type: Boolean,
			default: false,
		},
	},
};
</script>

<style lang="scss" scoped>
.q-item-section {
	&--icon {
		flex: auto;
	}
}
</style>
