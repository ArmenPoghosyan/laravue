<template>
	<ap-page>
		<ap-card>
			<ap-card-section class="row items-center q-gap-md">
				<span class="text-h6 q-mr-auto">{{ $t('pages.localizations.title') }}</span>

				<div class="row items-center q-gap-sm">
					<div class="row items-center q-gap-sm q-mr-auto">
						<ap-button size="sm" :label="$t('globals.sync')" color="primary" @click="sync" />
						<ap-button size="sm" :label="$t('globals.create')" color="primary" @click="show_dialog(null)" />
					</div>
				</div>
				<ap-select map-options emit-value :options="lists.languages" v-model="state.language" :class="{ 'full-width': $q.screen.lt.sm }" />
			</ap-card-section>

			<ap-separator />

			<ap-card-section class="q-py-sm text-bold row items-center q-gap-sm">
				{{ $t('pages.localizations.path') }}

				<q-breadcrumbs separator="/">
					<q-breadcrumbs-el v-for="item in state.path" :key="item.key" :label="item.key" :to="{ name: 'localizations.index', params: { id: item.id } }" />
				</q-breadcrumbs>

				<ap-button icon="content_copy" size="xs" round @click="copy_path" v-if="state.path?.length > 1" />
			</ap-card-section>

			<ap-separator />

			<ap-card-section no-padding class="overflow-hidden">
				<ap-table hide-bottom flat :columns="columns" :rows="state.list" color="primary" :loading="state.loading" :pagination="{ rowsPerPage: 1000 }">
					<template v-slot:body="props">
						<ap-tr @click="props.row.type == 'node' ? $router.push({ name: 'localizations.index', params: { id: props.row.id } }) : show_dialog(props.row)">
							<ap-td key="type" class="cursor-pointer">
								<ap-icon v-if="props.row.type == 'value'" name="sym_o_line_start_diamond" size="22px" color="primary" />
								<ap-icon v-else name="sym_o_network_node" size="22px" color="primary" />
							</ap-td>

							<ap-td key="key">
								{{ props.row.key }}
							</ap-td>

							<ap-td key="label">
								{{ props.row.label }}
							</ap-td>

							<ap-td key="value" v-html="props.row.value?.[state?.language] || ''" />

							<ap-td key="actions">
								<div class="no-wrap row justify-end q-gap-sm">
									<ap-button icon="content_copy" size="xs" round @click.stop="(event) => copy_path(event, props.row.key)" />
									<ap-button icon="edit" size="xs" round @click.stop="show_dialog(props.row)" />
									<ap-button icon="delete" color="negative" size="xs" round @click.stop="destroy(props.row)" />
								</div>
							</ap-td>
						</ap-tr>
					</template>
				</ap-table>
			</ap-card-section>
		</ap-card>

		<ap-dialog v-model="dialog.show">
			<ap-dialog-card style="max-width: 700px">
				<ap-dialog-header>
					<span class="text-h6">
						<template v-if="dialog.data.id">{{ $t('globals.edit') }}</template>
						<template v-else>{{ $t('globals.create') }}</template>
					</span>

					<ap-button flat round class="q-ml-auto" size="sm" icon="close" v-close-popup />
				</ap-dialog-header>

				<ap-separator />

				<ap-card-section class="column">
					<span class="text-negative inline-block q-mb-md" v-if="dialog.data.id && dialog.data.type == 'node'">{{ $t('pages.localizations.node_edit') }}</span>

					<div class="row no-wrap full-width q-gap-md">
						<ap-select class="full-width" :label="$t('pages.localizations.type')" map-options emit-value :options="localization_types" v-model="dialog.data.type" v-if="dialog.data.id == null" validate="type" />
						<ap-input class="full-width" :label="$t('pages.localizations.key')" v-model="dialog.data.key" validate="key" />
						<ap-input class="full-width" :label="$t('pages.localizations.label')" v-model="dialog.data.label" validate="label" />
					</div>
				</ap-card-section>

				<template v-if="dialog.data.type == 'value'">
					<ap-separator />

					<ap-card-section no-padding>
						<ap-tabs dense v-model="dialog.selected_language" class="full-width" align="justify">
							<ap-tab v-for="(language, index) in lists.languages" :key="index" :no-caps="false" :name="language.value" :label="language.value" />
						</ap-tabs>
					</ap-card-section>

					<ap-card-section no-padding>
						<ap-editor rich no-focus v-model="dialog.data.value[dialog.selected_language]" min-height="10rem" />
					</ap-card-section>
				</template>

				<ap-separator v-else />

				<ap-card-section class="row items-center justify-between">
					<ap-button flat :label="$t('globals.cancel')" color="primary" v-close-popup />
					<ap-button :label="$t('globals.save')" color="primary" :loading="dialog.loading" @click="save" />
				</ap-card-section>
			</ap-dialog-card>
		</ap-dialog>
	</ap-page>
</template>

<script>
import { core } from 'src/core';
import { onMounted, ref, watch } from 'vue';
import { copyToClipboard } from 'quasar';

export default {
	setup() {
		const state = ref({
			language: core.state.user.language,
			id: null,
			path: null,
			list: [],
			loading: false,
		});

		const dialog = ref({
			show: false,
			loading: false,
			selected_language: 'en',

			data: {
				id: null,
				language: null,
				parent_id: null,
				type: 'value',
				label: '',
				key: '',
				value: core.spreadLanguages(),
			},
		});

		const columns = [
			{
				name: 'type',
				align: 'left',
				label: '',
				field: 'type',
				sortable: true,
				style: 'width: 20px',
				headerStyle: 'width: 20px',
			},
			{
				name: 'key',
				align: 'left',
				label: core.locale.t('pages.localizations.key'),
				field: 'key',
				sortable: true,
			},
			{
				name: 'label',
				align: 'left',
				label: core.locale.t('pages.localizations.label'),
				field: 'label',
				sortable: true,
			},
			{
				name: 'value',
				align: 'left',
				label: core.locale.t('pages.localizations.value'),
				field: 'value',
				sortable: true,
			},
			{
				name: 'actions',
				align: 'right',
				label: '',
				field: 'actions',
				sortable: false,
			},
		];

		const localization_types = [
			{ value: 'value', label: core.locale.t('pages.localizations.value') },
			{ value: 'node', label: core.locale.t('pages.localizations.node') },
		];

		onMounted(() => {
			const id = core.router.currentRoute.value.params.id;
			if (id) {
				state.value.id = id;
				dialog.value.data.parent_id = id;
			}

			fetch();
		});

		watch(core.router.currentRoute, (route) => {
			const id = route.params.id;
			if (id != state.value.id) {
				state.value.id = id;
				dialog.value.data.parent_id = id;
				fetch();
			}
		});

		async function fetch() {
			let response = null;
			// tableLoading.value = true;
			state.value.loading = true;

			if (state.value.id) {
				response = await core.store.dispatch('localization/show', { id: state.value.id, language: state.value.language });
			} else {
				response = await core.store.dispatch('localization/index');
			}

			if (response && response.status) {
				state.value.list = response.localizations;
				let rootPath = [{ id: null, key: 'root' }];

				if (response.path) {
					state.value.path = [...rootPath, ...response.path];
				} else {
					state.value.path = rootPath;
				}
			}

			state.value.loading = false;
		}

		async function save() {
			core.clearErrors();

			dialog.value.loading = true;

			let response = null;

			if (dialog.value.data.id) {
				response = await core.store.dispatch('localization/update', dialog.value.data);
			} else {
				response = await core.store.dispatch('localization/store', dialog.value.data);
			}

			if (response.status) {
				dialog.value.show = false;
				fetch();
			} else if (response.statusCode == 422) {
				core.setErrors(response?.errors);
			}

			dialog.value.loading = false;
		}

		function show_dialog(localization = null) {
			dialog.value.show = true;
			dialog.value.loading = false;
			core.clearErrors();

			dialog.value.selected_language = 'en';

			if (localization) {
				dialog.value.data = { ...localization };
			} else {
				dialog.value.data = {
					id: null,
					language: state.value.language,
					parent_id: state.value.id,
					type: 'value',
					label: '',
					key: '',
					value: core.spreadLanguages(),
				};
			}
		}

		function copy_path(event, key = null) {
			let path = state.value.path.filter((item) => item.id).map((item) => item.key);

			if (key) {
				path.push(key);
			}

			path = path.join('.');

			if (event.ctrlKey) {
				path = `{{ $t('${path}') }}`;
			} else if (event.shiftKey) {
				path = `$t('${path}')`;
			}

			copyToClipboard(path);
		}

		function destroy(localization) {
			async function process_delete() {
				const response = await core.store.dispatch('localization/destroy', localization.id);
				if (response.status) {
					await fetch();
				}
			}

			core.$q
				.dialog({
					title: core.locale.t('globals.confirm'),
					message: core.locale.t('pages.localizations.delete'),
					cancel: core.locale.t('globals.cancel'),
					ok: core.locale.t('globals.yes'),
				})
				.onOk(async () => {
					if (localization.type == 'node') {
						core.$q
							.dialog({
								title: core.locale.t('globals.please_note'),
								message: core.locale.t('pages.localizations.delete_node'),
							})
							.onOk(process_delete);
					} else {
						process_delete();
					}
				});
		}

		async function sync() {
			state.value.sync_loading = true;

			const response = await core.store.dispatch('localization/sync');

			if (response.status) {
				core.$q.notify({
					type: 'positive',
					message: core.locale.t('pages.localizations.synced'),
				});
			} else {
				core.$q.notify({
					type: 'negative',
					message: core.locale.t('pages.localizations.sync_failed'),
				});
			}

			state.value.sync_loading = false;
		}

		return {
			state,
			dialog,
			columns,
			localization_types,

			//
			save,
			destroy,
			sync,
			//
			show_dialog,
			copy_path,
		};
	},
};
</script>
