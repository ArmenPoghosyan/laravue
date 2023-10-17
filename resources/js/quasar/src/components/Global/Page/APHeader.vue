<template>
	<q-header>
		<slot />
	</q-header>
</template>

<style lang="scss" scoped>
.q-header {
	height: 60px;
	padding: map-get($space-sm, 'y') map-get($space-md, 'x');
}
</style>
