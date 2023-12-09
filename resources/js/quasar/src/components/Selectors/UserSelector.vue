<template>
	<ap-select v-model="selected" :multiple="multiple" :use-chips="multiple" emit-value map-options stack-label use-input input-debounce="200" :label="$t('globals.user')" :options="list" @filter="on_filter" @filter-abort="on_filter_abort">
		<template v-slot:no-option>
			<q-item>
				<q-item-section class="text-grey"> No results </q-item-section>
			</q-item>
		</template>

		<template v-slot:option="{ itemProps, opt, selected, toggleOption }">
			<ap-item v-bind="itemProps">
				<q-item-section side v-if="multiple">
					<ap-checkbox :model-value="selected" @update:model-value="toggleOption(opt)" />
				</q-item-section>
				<q-item-section>
					<q-item-label v-html="opt.label" />
				</q-item-section>
			</ap-item>
		</template>
	</ap-select>
</template>

<script>
import { core } from 'src/core';
import { onMounted, ref, watch } from 'vue';
export default {
	emits: ['update:modelValue', 'selected'],
	props: {
		modelValue: {},
		multiple: {
			type: Boolean,
			default: false,
		},
		includeAll: {
			type: Boolean,
			default: false,
		},
	},
	setup(props, { emit }) {
		const list = ref([]);
		const selected = ref(props.multiple ? [] : null);

		onMounted(() => {
			if (props.modelValue) {
				selected.value = props.multiple
					? props.modelValue.map((item) => {
							return {
								label: item.full_name,
								value: item.id,
							};
					  })
					: {
							label: props.modelValue?.full_name,
							value: props.modelValue?.id,
					  };
				//
			}
		});

		watch(selected, (value) => {
			if (value) {
				if (typeof value === 'number') {
					emit(
						'selected',
						list.value.find((item) => item.value === value)
					);
				} else {
					emit(
						'selected',
						list.value.filter((item) => {
							return value.includes(item.value);
						})
					);
				}
			}
			emit(
				'update:modelValue',
				props.multiple
					? value.map((item) => {
							return typeof item === 'number' ? item : item.value;
					  })
					: typeof value === 'number'
					? value
					: value.value
				//
			);
		});

		async function on_filter(value, update, abort) {
			value = value.trim();
			if (!value) return abort();

			const response = await core.store.dispatch('users/index', {
				limit: 10,
				sort_by: 'id',
				direction: 'desc',
				filters: {
					query: value,
				},
			});

			if (response.status) {
				update(() => {
					list.value = response.users.data.map((item) => {
						let data = {
							label: item.full_name,
							value: item.id,
						};

						if (props.includeAll) {
							data['user'] = item;
						}

						return data;
					});
				});
			} else {
				abort();
			}
		}

		function on_filter_abort() {
			//
		}

		return {
			list,
			selected,

			//
			on_filter,
			on_filter_abort,
		};
	},
};
</script>
