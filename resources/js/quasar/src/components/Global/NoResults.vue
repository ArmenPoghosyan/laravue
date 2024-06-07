<template>
	<div class="q-pa-xl column flex-center text-grey-6 q-gap-sm">
		<span class="text-h4">{{ title }}</span>
		<span class="text-body1">{{ subtitle }}</span>
	</div>
</template>

<script>
export default {
	props: {
		title: {
			type: String,
			default: '↜(o_O)↝',
		},
		subtitle: {
			type: String,
			default: 'Nothing to see here...',
		},
	},
};
</script>
