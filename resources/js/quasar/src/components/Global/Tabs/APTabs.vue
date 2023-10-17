<template>
	<q-tabs ref="tabs">
		<slot />
	</q-tabs>
</template>

<script>
import { onMounted, ref } from 'vue';

export default {
	props: {
		mouseScroll: {
			type: Boolean,
			default: false,
		},
	},

	setup(props) {
		const tabs = ref(null);

		onMounted(() => {
			if (props.mouseScroll && tabs.value && tabs.value.$el) {
				let el = tabs.value.$el;
				el.addEventListener('wheel', (event) => {
					event.preventDefault();
					el.querySelector('.q-tabs__content').scrollLeft += event.deltaY * 2;
				});
			}
		});

		return { tabs };
	},
};
</script>

<style lang="scss">
.q-tabs {
	&__content {
		scroll-behavior: smooth;
	}
}
</style>
