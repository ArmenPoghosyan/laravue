<template>
	<q-card :class="{ 'rounded-12': rounded }">
		<slot />
	</q-card>
</template>

<script>
export default {
	props: {
		rounded: {
			type: Boolean,
			default: true,
		},
	},
};
</script>
