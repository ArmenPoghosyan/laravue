<template>
	<ap-tabs mouse-scroll align="left">
		<ap-tab v-for="(language, index) in list" :key="index" :name="language?.value">
			<div class="row items-center q-gap-xs no-wrap">
				<img :src="`/images/flags/${language.value}.svg`" class="bordered" :title="$t(`globals.language.${language.value}`)" width="20" />
				<span v-if="short">{{ language?.value?.toUpperCase() }}</span>
				<span v-else>{{ language?.label }}</span>
			</div>
		</ap-tab>
	</ap-tabs>
</template>

<script>
import { core } from 'src/core';
import { computed, ref } from 'vue';

export default {
	props: {
		showFlag: {
			type: Boolean,
			default: true,
		},

		short: {
			type: Boolean,
			default: false,
		},

		languages: {
			type: Array,
			default: () => null,
		},
	},

	setup(props) {
		const list = computed(() => {
			if (props.languages) {
				return props.languages.map((language) => {
					return {
						value: language,
						label: core.locale.t(`globals.language.${language}`),
					};
				});
			}

			return core.lists.language_list.value;
		});

		return { list };
	},
};
</script>
