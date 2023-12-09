<template>
	<q-td v-if="props?.colsMap?.[key]" v-bind="props">
		<slot />
	</q-td>
</template>

<script>
import { getCurrentInstance } from 'vue';

export default {
	props: {
		props: {},
	},
	setup() {
		return { key: getCurrentInstance()?.vnode?.key || '' };
	},
};
</script>
