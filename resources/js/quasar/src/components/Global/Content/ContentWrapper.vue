<template>
	<div class="content__wrapper" :class="{ 'content__wrapper--dense': dense, 'content__wrapper--padding-y': paddingY }">
		<slot />
	</div>
</template>

<script>
export default {
	props: {
		dense: {
			type: Boolean,
			default: false,
		},

		paddingY: {
			type: Boolean,
			default: false,
		},
	},
};
</script>
