<template>
	<span v-if="validate && validation.error(validate)" class="validationMessage">{{ validation.errorMessage(validate) }}</span>
</template>

<script>
export default {
	props: {
		validate: {
			type: String,
			default: null,
		},
	},
};
</script>

<style lang="scss" scoped>
.validationMessage {
	//
	color: $negative;
	font-size: 11px;
}
</style>
