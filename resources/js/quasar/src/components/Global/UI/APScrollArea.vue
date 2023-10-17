<template>
	<div
		ref="scrollArea"
		class="scrollable"
		:class="{ 'scrollable--scroll-visible': hasScroll, 'scrollable--no-padding': noPadding, 'scrollable--scroll-40': scroll40 }"
		:style="(maxHeight && `max-height: ${maxHeight};`) + (maxWidth && `max-width: ${maxWidth};`)"
	>
		<slot />
	</div>
</template>

<script>
import { ref, onMounted, onUpdated } from 'vue';

export default {
	props: {
		maxHeight: {
			default: null,
		},

		maxWidth: {
			default: null,
		},

		delay: {
			default: null,
		},

		noPadding: {
			type: Boolean,
			default: false,
		},

		scroll40: {
			type: Boolean,
			default: false,
		},
	},

	setup(props) {
		const scrollArea = ref(null);
		const hasScroll = ref(false);

		onMounted(() => checkScroll());
		onUpdated(() => checkScroll(props.delay));

		function checkScroll(delay = null) {
			let check = () => {
				if (scrollArea.value) {
					hasScroll.value = scrollArea.value.scrollHeight > scrollArea.value.clientHeight;
				} else {
					hasScroll.value = false;
				}
			};

			if (delay != null) {
				setTimeout(check, delay);
			} else {
				check();
			}
		}

		return {
			scrollArea,
			hasScroll,
		};
	},
};
</script>

<style lang="scss">
.scrollable {
	overflow: auto;

	&--scroll-visible:not(.scrollable--no-padding) {
		padding-right: 4px;
	}

	&::-webkit-scrollbar {
		width: 6px;
		height: 6px;
		cursor: pointer;
	}

	&::-webkit-scrollbar-thumb {
		background-color: $secondary;
		border-radius: 10px;
	}

	&::-webkit-scrollbar-track {
		background-color: rgba($secondary, 0.1);
		border-radius: 10px;
	}

	&--scroll-40 {
		&::-webkit-scrollbar-track {
			margin-top: 40px;
		}
	}
}
</style>
