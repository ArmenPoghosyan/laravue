<template>
	<q-table ref="tableRef" :columns="columns" :visible-columns="visible_columns" :rows-per-page-label="$t('globals.pagination.records')" :pagination-label="get_pagination_label">
		<slot />

		<template v-slot:body="scope" v-if="availableSlots.includes('body')">
			<slot name="body" v-bind="scope" />
		</template>

		<template v-slot:pagination="scope">
			<slot name="pagination" v-bind="scope" />

			<div class="row items-center q-gap-xs no-wrap">
				{{
					$t('globals.pagination.range', {
						start: scope.pagination.rowsPerPage === 0 ? 0 : (scope.pagination.page - 1) * scope.pagination.rowsPerPage + 1,
						end:
							scope.pagination.rowsPerPage === 0
								? scope.pagination.rowsNumber
								: scope.pagination.page * scope.pagination.rowsPerPage > scope.pagination.rowsNumber
								? scope.pagination.rowsNumber
								: scope.pagination.page * scope.pagination.rowsPerPage,
						total: scope.pagination.rowsNumber,
					})
				}}

				<div class="q-ml-md row no-wrap items-center q-gap-xs">
					<ap-button v-if="scope.pagination.page > 2" icon="keyboard_double_arrow_left" color="grey-8" round size="12px	" flat :disable="scope.isFirstPage" @click="scope.firstPage" />
					<ap-button icon="chevron_left" color="grey-8" round size="12px	" flat :disable="scope.isFirstPage" @click="scope.prevPage" />
					<ap-button icon="chevron_right" color="grey-8" round size="12px	" flat :disable="scope.isLastPage" @click="scope.nextPage" />
					<ap-button v-if="scope.pagination.page < scope.pagesNumber" icon="keyboard_double_arrow_right" color="grey-8" round size="12px	" flat :disable="scope.isLastPage" @click="scope.lastPage" />

					<ap-button v-if="name" round flat icon="settings" size="10px" color="primary">
						<ap-menu class="bg-white">
							<ap-list separator>
								<ap-item v-for="(column, index) in columns" :key="index" clickable dense @click="toggle_column(column.name)">
									<ap-item-section icon>
										<ap-checkbox size="32px" dense :model-value="visible_columns.includes(column.name)" @click.prevent="toggle_column(column.name)" />
									</ap-item-section>

									<ap-item-section>
										<ap-item-label>{{ column.label }}</ap-item-label>
									</ap-item-section>
								</ap-item>
							</ap-list>
						</ap-menu>
					</ap-button>
				</div>
			</div>
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

		columns: {},

		name: {
			default: null,
		},
	},

	setup(props, { slots }) {
		const tableRef = ref(null);
		const availableSlots = ref(Object.keys(slots));
		const visible_columns = ref();

		if (props.name) {
			onMounted(() => {
				let columns = JSON.parse(localStorage.getItem(`table.${props.name}.visible_columns`));

				if (columns) {
					visible_columns.value = columns;
				} else {
					visible_columns.value = props.columns.map((column) => column.name);
				}
			});
		} else {
			visible_columns.value = props.columns.map((column) => column.name);
		}

		if (props.useServer) {
			onMounted(() => {
				tableRef.value && tableRef.value?.requestServerInteraction();
			});
		}

		function get_pagination_label(start, end, total) {
			return core.locale.t('globals.pagination.range', { start, end, total });
		}

		function toggle_column(name) {
			if (props.name) {
				if (visible_columns.value.includes(name)) {
					visible_columns.value = visible_columns.value.filter((column) => column !== name);
				} else {
					visible_columns.value.push(name);
				}

				localStorage.setItem(`table.${props.name}.visible_columns`, JSON.stringify(visible_columns.value));
			}
		}

		return {
			tableRef,
			availableSlots,
			visible_columns,
			//
			get_pagination_label,
			toggle_column,
		};
	},
};
</script>
