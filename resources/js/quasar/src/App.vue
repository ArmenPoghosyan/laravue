<template>
	<transition-group enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
		<router-view v-if="state.app.loaded" key="app" />

		<div v-else key="loader" class="absolute-full column bg-white text-primary flex flex-center q-gap-lg">
			<ap-spinner size="60px" />
		</div>
	</transition-group>
</template>

<script>
import { defineComponent, ref } from 'vue';
import { core } from 'src/core';

export default defineComponent({
	name: 'App',

	setup() {
		const loaded = ref(false);

		init();

		async function init() {
			await core.store.dispatch('app/init');
			core.router.protectRoute();
		}

		return {
			loaded,
		};
	},
});
</script>
