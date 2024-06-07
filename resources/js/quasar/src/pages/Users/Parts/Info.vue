<template>
	<div class="column full-width">
		<ap-markup-table flat>
			<tbody>
				<tr>
					<td>{{ $t('globals.user_fields.first_name') }}</td>
					<td>{{ user.first_name }}</td>
				</tr>

				<tr>
					<td>{{ $t('globals.user_fields.last_name') }}</td>
					<td>{{ user.last_name }}</td>
				</tr>

				<tr>
					<td>{{ $t('globals.user_fields.phone') }}</td>
					<td>{{ user.phone || '-' }}</td>
				</tr>

				<tr>
					<td>{{ $t('globals.user_fields.email') }}</td>
					<td>{{ user.email }}</td>
				</tr>

				<tr>
					<td>{{ $t('globals.user_fields.type') }}</td>
					<td>{{ $t(`globals.user_types.${user.type}`) }}</td>
				</tr>

				<tr>
					<td>{{ $t('globals.user_fields.age') }}</td>
					<td>{{ user.age }}</td>
				</tr>

				<tr>
					<td>{{ $t('globals.user_fields.birth_date') }}</td>
					<td>{{ user.birth_date ? nice_date(user.birth_date) : '-' }}</td>
				</tr>
			</tbody>
		</ap-markup-table>

		<div v-if="user.type == 'admin'" class="row q-pa-md justify-end">
			<ap-button outline icon="edit" :label="$t('globals.edit')" color="primary" @click="open_edit" />
		</div>

		<ap-dialog v-model="editing.open">
			<ap-dialog-card>
				<ap-dialog-header>
					<span class="text-h6 q-mr-auto">{{ $t('globals.edit') }}</span>
					<ap-button v-close-popup round flat icon="close" size="12px" />
				</ap-dialog-header>

				<ap-card-section class="row q-col-gutter-md">
					<ap-select v-if="$user.type == 'admin'" class="col-xs-12" :label="$t('globals.user_fields.type')" :options="lists.user_types.value" v-model="editing.data.type" map-options emit-value validate="type" />

					<ap-input v-model="editing.data.first_name" class="col-xs-12 col-sm-6" :label="$t('globals.user_fields.first_name')" validate="first_name" />
					<ap-input v-model="editing.data.last_name" class="col-xs-12 col-sm-6" :label="$t('globals.user_fields.last_name')" validate="last_name" />
					<ap-input v-model="editing.data.phone" class="col-xs-12 col-sm-6" :label="$t('globals.user_fields.phone')" validate="phone" />
					<ap-date-picker v-model="editing.data.birth_date" class="col-xs-12 col-sm-6" :label="$t('globals.user_fields.birth_date')" validate="birth_date" />
				</ap-card-section>

				<ap-card-section class="row justify-between">
					<ap-button v-close-popup flat :label="$t('globals.cancel')" />
					<ap-button color="primary" :label="$t('globals.continue')" :loading="state.loading" @click="save" />
				</ap-card-section>
			</ap-dialog-card>
		</ap-dialog>
	</div>
</template>

<script>
import { core } from 'src/core';
import { onMounted, ref } from 'vue';

export default {
	emits: ['update:modelValue'],
	props: {
		modelValue: {
			type: Object,
			default: () => ({}),
		},
	},

	setup(props, { emit }) {
		const user = ref(props.modelValue);

		const rows = ref([]);

		const editing = ref({
			open: false,
			loading: false,
			data: {
				id: null,
				type: null,
				first_name: null,
				last_name: null,
				phone: null,
				birth_date: null,
			},
		});

		function open_edit() {
			editing.value.open = true;
			editing.value.data = {
				id: user.value.id,
				type: user.value.type,
				first_name: user.value.first_name,
				last_name: user.value.last_name,
				phone: user.value.phone,
				birth_date: user.value.birth_date,
			};
		}

		async function save() {
			editing.value.loading = true;

			core.clearErrors();
			const response = await core.store.dispatch('users/update', editing.value.data);

			if (response.status) {
				editing.value.open = false;
				core.$q.notify({
					type: 'positive',
					message: core.locale.t('globals.saved'),
				});

				user.value = {
					...user.value,
					...(response.user || {}),
				};
				emit('update:modelValue', user.value);
			} else if (response.statusCode == 422) {
				core.setErrors(response.errors);
			} else {
				core.$q.notify({
					type: 'negative',
					message: core.locale.t('globals.errors.unknown'),
				});
			}

			editing.value.loading = false;
		}

		return {
			user,
			editing,
			//
			open_edit,
			save,
		};
	},
};
</script>
