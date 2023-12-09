<template>
	<q-layout view="hHh lpR fFf">
		<q-page-container>
			<ap-page class="relative-position cms q-px-md q-py-sm">
				<div class="cms__wrapper bg-white rounded-12">
					<router-view />
				</div>
			</ap-page>
		</q-page-container>
	</q-layout>
</template>

<script>
export default {
	name: 'AuthLayout',
};
</script>

<style lang="scss" scoped>
.cms {
	background-color: $accent;
	background-size: cover;
	background-position: right;
	padding: map-get($space-xl, 'x');

	@media (max-width: $breakpoint-sm) {
		padding: map-get($space-lg, 'x');
	}

	display: flex;
	justify-content: center;

	&__wrapper {
		width: 100%;
		max-width: 80%;
		max-height: 100%;

		@media (max-width: $breakpoint-sm) {
			max-width: 100%;
		}
	}
}
</style>
