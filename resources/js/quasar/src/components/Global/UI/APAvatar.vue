<template>
	<q-avatar :class="{ 'bordered bordered-light overflow-hidden': bordered }">
		<slot />
	</q-avatar>
</template>

<script>
export default {
	props: {
		bordered: {
			type: Boolean,
			default: false,
		},
	},
};
</script>
