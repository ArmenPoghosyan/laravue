<template>
	<q-table ref="tableRef" :rows-per-page-label="$t('globals.pagination.records')" :pagination-label="get_pagination_label">
		<slot />

		<template v-slot:body="scope" v-if="availableSlots.includes('body')">
			<slot name="body" v-bind="scope" />
		</template>

		<template v-slot:pagination="scope" v-if="availableSlots.includes('pagination')">
			<slot name="pagination" v-bind="scope" />
		</template>

		<template v-slot:bottom="scope" v-if="availableSlots.includes('bottom')">
			<slot name="bottom" v-bind="scope" />
		</template>

		<template v-slot:top-right="scope" v-if="availableSlots.includes('top-right')">
			<slot name="top-right" v-bind="scope" />
		</template>
	</q-table>
</template>

<script>
import { core } from 'src/core';
import { onMounted, ref } from 'vue';

export default {
	props: {
		useServer: {
			type: Boolean,
			default: false,
		},
	},
	setup(props, { slots }) {
		const tableRef = ref(null);
		const availableSlots = ref(Object.keys(slots));

		if (props.useServer) {
			onMounted(() => {
				tableRef.value && tableRef.value?.requestServerInteraction();
			});
		}

		function get_pagination_label(start, end, total) {
			return core.locale.t('globals.pagination.range', { start, end, total });
		}

		return {
			tableRef,
			availableSlots,
			//
			get_pagination_label,
		};
	},
};
</script>
