<template>
	<q-tabs ref="tabs" :class="{ 'q-tabs--vertical-center': verticalCenter }" active-color="primary">
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

		verticalCenter: {
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
					el.querySelector('.q-tabs__content').classList.add('q-tabs__content--smooth');
					el.querySelector('.q-tabs__content').scrollLeft += event.deltaY * 2;

					setTimeout(() => {
						el.querySelector('.q-tabs__content').classList.remove('q-tabs__content--smooth');
					}, 100);
				});
			}
		});

		return { tabs };
	},
};
</script>

<style lang="scss">
.q-tabs {
	$this: &;
	&__content {
		&--smooth {
			scroll-behavior: smooth;
		}
	}

	&--vertical-center {
		#{$this}__content {
			display: flex !important;
			flex-direction: column;
			justify-content: center;
		}
	}
}
</style>
