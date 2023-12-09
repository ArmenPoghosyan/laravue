<template>
	<ap-dialog v-model="is_open">
		<ap-card class="fit bg-grey-9 relative-position" style="max-width: 90%; max-height: 75%">
			<ap-button v-close-popup round color="white" text-color="text" size="12px" icon="close" class="absolute-top-right q-ma-md top" />

			<ap-img class="fit" fit="contain" :src="state.app.media_popup.media" />
		</ap-card>
	</ap-dialog>
</template>

<script>
import { core } from 'src/core';
import { computed, ref } from 'vue';

export default {
	setup() {
		const is_open = computed({
			get: () => core.store.state.app.media_popup.open,
			set: (value) => core.store.commit('app/media_popup', { open: value, media: null }),
		});

		return {
			is_open,
		};
	},
};
</script>
