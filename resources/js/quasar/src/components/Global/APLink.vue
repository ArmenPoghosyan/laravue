<template>
	<router-link :to="to" class="text-secondary">
		<slot />
	</router-link>
</template>

<script>
export default {
	name: 'APLink',
	props: {
		to: {
			default: '/',
		},
	},
};
</script>
