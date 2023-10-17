<template>
	<q-layout view="hHh lpR fFf">
		<q-page-container>
			<ap-page class="relative-position auth">
				<div class="auth__wrapper bg-white q-pa-md rounded-12">
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
.auth {
	background-color: $primary;
	background-image: url('/images/auth_bg.jpg');
	background-size: cover;
	background-position: right;
	padding: map-get($space-md, 'x');

	display: flex;
	align-items: center;
	justify-content: center;

	&__wrapper {
		width: 100%;
		max-width: 450px;
	}
}
</style>
