<template>
	<ap-page>
		<ap-card>
			<ap-card-section class="row items-center q-gap-md">
				<span class="text-h6 q-mr-auto">{{ $t('pages.users.title') }}</span>

				<ap-button v-if="$user?.type == 'admin'" hide-label-sm outline color="primary" icon="add" :label="$t('globals.add')" @click="open_invite" />
			</ap-card-section>

			<ap-separator />

			<ap-card-section>
				<div class="row items-center q-col-gutter-md">
					<ap-input v-model="state.filters.query" no-padding-right debounce="300" @update:modelValue="fetch()" :class="{ 'full-width': $q.screen.lt.sm }" class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
						<template #prepend>
							<ap-icon name="sym_o_search" size="24px" />
						</template>
					</ap-input>
					<ap-select v-model="state.filters.type" map-options emit-value :options="lists.prependAll(lists.user_types.value)" :label="$t('globals.user_fields.type')" @update:modelValue="fetch()" class="col-xs-12 col-sm-6 col-md-2 col-lg-1" />
					<ap-select v-model="state.filters.status" :options="lists.prependAll(lists.user_statuses.value)" map-options emit-value :label="$t('globals.status')" @update:modelValue="fetch()" class="col-xs-12 col-sm-6 col-md-2 col-lg-1" />
					<ap-date-picker v-model="state.filters.date" :label="$t('globals.user_fields.registration_date')" range @update:modelValue="fetch()" class="col-xs-12 col-sm-6 col-md-3 col-lg-2" />
				</div>
			</ap-card-section>

			<ap-card-section no-padding>
				<ap-table name="users" use-server flat :columns="columns" :rows="state.list" :loading="state.loading" v-model:pagination="pagination" @request="fetch">
					<template #body="props">
						<ap-tr class="cursor-pointer" @click="$router.push({ name: 'user.index', params: { id: props.row.id } })">
							<ap-td :props="props" key="avatar">
								<ap-avatar bordered rounded size="40px">
									<ap-img v-if="props.row.avatar" :src="media(props.row.avatar, 's')" fit="cover" class="fit cursor-pointer" @click.stop.prevent="open_media_popup(media(props.row.avatar))" />
									<ap-icon v-else name="sym_o_person" color="grey-6" />
								</ap-avatar>
							</ap-td>

							<ap-td :props="props" key="first_name">{{ props.row.first_name }}</ap-td>
							<ap-td :props="props" key="last_name">{{ props.row.last_name }}</ap-td>
							<ap-td :props="props" key="birth_date">{{ props.row.age }}</ap-td>
							<ap-td :props="props" key="phone">{{ props.row.phone || '-' }}</ap-td>
							<ap-td :props="props" key="email">{{ props.row.email }}</ap-td>
							<ap-td :props="props" key="type">{{ $t(`globals.user_types.${props.row.type}`) }}</ap-td>
							<ap-td :props="props" key="deleted_at" class="text-uppercase text-weight-bold" :class="{ 'text-positive': props.row.status == 'active', 'text-negative': props.row.status == 'archived' }">
								{{ props.row.status == 'active' ? $t('globals.active') : $t('globals.archived') }}
							</ap-td>
							<ap-td :props="props" key="created_at">{{ nice_date(props.row.created_at, true) }}</ap-td>
						</ap-tr>
					</template>
				</ap-table>
			</ap-card-section>
		</ap-card>

		<ap-dialog v-model="invite.open">
			<ap-dialog-card>
				<ap-dialog-header>
					<span class="text-h6 q-mr-auto">{{ $t('pages.users.add.title') }}</span>
					<ap-button icon="close" size="12px" v-close-popup round flat />
				</ap-dialog-header>

				<ap-separator />

				<ap-card-section class="row q-col-gutter-md">
					<ap-select v-if="$user.type == 'admin'" class="col-xs-12" :label="$t('globals.user_fields.type')" :options="lists.user_types.value" v-model="invite.data.type" map-options emit-value validate="type" />

					<ap-input class="col-xs-12 col-sm-6" type="first_name" v-model="invite.data.first_name" :label="$t('globals.user_fields.first_name')" validate="first_name" />
					<ap-input class="col-xs-12 col-sm-6" type="last_name" v-model="invite.data.last_name" :label="$t('globals.user_fields.last_name')" validate="last_name" />
					<ap-input class="col-xs-12 col-sm-6" type="email" v-model="invite.data.email" :label="$t('globals.user_fields.email')" validate="email" />
					<ap-input class="col-xs-12 col-sm-6" v-model="invite.data.phone" :label="$t('globals.user_fields.phone')" validate="phone" />
					<ap-date-picker v-model="invite.data.birth_date" class="col-xs-12 col-sm-6" :label="$t('globals.user_fields.birth_date')" validate="birth_date" />
					<!-- <ap-select map-options emit-value :options="lists.user_types.value" v-model="invite.data.type" :label="$t('globals.user_fields.type')" /> -->
				</ap-card-section>

				<ap-separator />

				<ap-card-section class="row items-center justify-between">
					<ap-button flat :label="$t('globals.cancel')" v-close-popup />
					<ap-button color="primary" :label="$t('globals.add')" :loading="invite.loading" @click="invite_user" />
				</ap-card-section>
			</ap-dialog-card>
		</ap-dialog>
	</ap-page>
</template>

<script>
import { core } from 'src/core';
import { computed, ref } from 'vue';
import moment from 'moment';

export default {
	setup() {
		const pagination = core.paginator(20);

		const state = ref({
			list: [],

			filters: {
				query: '',
				type: null,
				status: null,

				date: {
					from: null, //moment().subtract(1, 'month').format('YYYY-MM-DD'),
					to: null, // moment().format('YYYY-MM-DD'),
				},
			},
		});

		const invite = ref({
			open: false,
			loading: false,

			data: {
				email: '',
				phone: '',
				type: 'user',
				birth_date: '',
				first_name: '',
				last_name: '',
			},
		});

		const columns = [
			{
				name: 'avatar',
				align: 'left',
				label: core.locale.t('globals.multimedia_types.photo'),
				sortable: false,
				style: 'width: 60px',
				headerStyle: 'width: 60px',
			},
			{
				name: 'first_name',
				align: 'left',
				label: core.locale.t('globals.user_fields.first_name'),
				sortable: true,
			},
			{
				name: 'last_name',
				align: 'left',
				label: core.locale.t('globals.user_fields.last_name'),
				sortable: true,
			},
			{
				name: 'birth_date',
				align: 'left',
				label: core.locale.t('globals.user_fields.age'),
				sortable: true,
			},
			{
				name: 'phone',
				align: 'left',
				label: core.locale.t('globals.user_fields.phone'),
				sortable: true,
			},
			{
				name: 'email',
				align: 'left',
				label: core.locale.t('globals.user_fields.email'),
				sortable: true,
			},
			{
				name: 'type',
				align: 'left',
				label: core.locale.t('globals.user_fields.type'),
				sortable: true,
			},
			{
				name: 'deleted_at',
				align: 'left',
				label: core.locale.t('globals.status'),
				sortable: true,
			},
			{
				name: 'created_at',
				align: 'left',
				label: core.locale.t('globals.user_fields.registration_date'),
				sortable: true,
			},
		];

		async function fetch(props = null) {
			state.value.loading = true;

			if (!props) {
				props = { pagination: pagination.value.reset() };
			}

			const response = await core.store.dispatch('users/index', {
				...props.pagination.normalize(),
				filters: state.value.filters,
				all: true,
			});

			if (response.status) {
				state.value.list = response?.users?.data || [];
				props.pagination.setTotal(response?.users?.total || 0);
			}

			pagination.value.fromProps(props.pagination);

			state.value.loading = false;
		}

		function reset_invite() {
			invite.value.data = {
				email: '',
				type: 'user',

				first_name: '',
				last_name: '',
			};
		}

		function open_invite() {
			reset_invite();
			invite.value.open = true;
		}

		async function invite_user() {
			invite.value.loading = true;
			core.clearErrors();

			const response = await core.store.dispatch('users/store', invite.value.data);

			if (response.status) {
				fetch();
				invite.value.open = false;
			} else if (response.statusCode == 422) {
				core.setErrors(response.errors);
			} else {
				core.$q.notify({
					type: 'negative',
					message: core.locale.t('globals.errors.unknown'),
				});
			}

			invite.value.loading = false;
		}

		return {
			state,
			pagination,
			invite,
			columns,
			//
			fetch,
			open_invite,
			invite_user,
		};
	},
};
</script>
