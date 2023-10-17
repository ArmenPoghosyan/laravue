<template>
	<q-card-section :class="{ 'no-padding': noPadding }">
		<slot />
	</q-card-section>
</template>

<script>
export default {
	props: {
		noPadding: {
			type: Boolean,
			default: false,
		},
	},
};
</script>
