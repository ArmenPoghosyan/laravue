<template>
	<swiper :modules="modules" :breakpoints="breakpoints" :slides-per-view="$q.screen.gt.sm ? 4 : 6" :space-between="50" navigation :pagination="{ clickable: true }">
		<swiper-slide v-for="(photo, index) in photos" :key="index">
			<ap-img :src="photo" class="fit rounded-12 bordered bordered-light" />
		</swiper-slide>
	</swiper>
</template>

<script>
import { Navigation, Pagination, A11y } from 'swiper/modules';

import { Swiper, SwiperSlide } from 'swiper/vue';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

export default {
	components: { Swiper, SwiperSlide },

	props: {
		photos: {
			type: Array,
			default: () => [],
		},
	},

	setup() {
		return {
			breakpoints: {
				0: {
					slidesPerView: 1,
				},
				400: {
					slidesPerView: 2,
					spaceBetween: 8,
				},
				576: {
					slidesPerView: 3,
					spaceBetween: 16,
				},
				768: {
					slidesPerView: 4,
					spaceBetween: 16,
				},
				1000: {
					slidesPerView: 6,
					spaceBetween: 16,
				},
			},
			modules: [Navigation, Pagination, A11y],
		};
	},
};
</script>

<style lang="scss">
:root {
	--swiper-theme-color: #{$primary};
}
</style>
