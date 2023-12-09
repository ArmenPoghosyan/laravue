<template>
	<ap-page>
		<ap-card>
			<ap-card-section class="row items-center">
				<span class="text-h6 q-mr-auto">{{ $t('pages.profile.title') }}</span>

				<ap-button color="primary" :label="$t('pages.profile.change_password')" size="12px" push v-password="open_password_dialog" />
			</ap-card-section>

			<ap-card-section class="row q-col-gutter-md">
				<ap-input class="col-xs-12 col-sm-4" :label="$t('globals.user_fields.first_name')" v-model="state.data.first_name" validate="first_name" @update:modelValue="state.changed = true" />
				<ap-input class="col-xs-12 col-sm-4" :label="$t('globals.user_fields.last_name')" v-model="state.data.last_name" validate="last_name" @update:modelValue="state.changed = true" />
				<ap-input class="col-xs-12 col-sm-4" :label="$t('globals.user_fields.phone')" v-model="state.data.phone" validate="phone" @update:modelValue="state.changed = true" />
				<ap-date-picker class="col-xs-12 col-sm-4" :label="$t('globals.user_fields.birth_date')" v-model="state.data.birth_date" validate="birth_date" @update:modelValue="state.changed = true" />
				<ap-input class="col-xs-12 col-sm-4" :label="$t('globals.user_fields.email')" v-model="state.data.email" v-password="() => open_email_dialog()" />
			</ap-card-section>

			<ap-card-section class="row items-center justify-end">
				<ap-button v-if="state.changed" color="primary" :label="$t('globals.save')" @click="save" :loading="state.loading" />
			</ap-card-section>
		</ap-card>

		<ap-dialog v-model="dialog.password.open">
			<ap-dialog-card>
				<ap-dialog-header>
					<span class="text-h6 q-mr-auto">{{ $t('pages.profile.change_password') }}</span>
					<ap-button v-close-popup flat round size="12px" icon="close" />
				</ap-dialog-header>

				<ap-card-section class="column q-gap-md">
					<ap-input type="password" :label="$t('globals.user_fields.password')" v-model="dialog.password.data.password" validate="password" />
					<ap-input type="password" :label="$t('globals.user_fields.password_confirm')" v-model="dialog.password.data.password_confirmation" validate="password_confirmation" />
				</ap-card-section>

				<ap-card-section class="row items-center justify-between">
					<ap-button v-close-popup flat :label="$t('globals.cancel')" />
					<ap-button color="primary" :loading="dialog.password.loading" @click="change_password" :label="$t('globals.change')" />
				</ap-card-section>
			</ap-dialog-card>
		</ap-dialog>

		<ap-dialog v-model="dialog.email.open">
			<ap-dialog-card>
				<ap-dialog-header>
					<span class="text-h6 q-mr-auto">{{ $t('pages.profile.change_email') }}</span>
					<ap-button v-close-popup flat round size="12px" icon="close" />
				</ap-dialog-header>

				<ap-card-section class="column q-gap-md">
					<span v-if="dialog.email.sent" class="flex flex-center q-pa-md"> {{ $t('pages.profile.email_change_sent') }} </span>

					<ap-input v-else type="email" :label="$t('globals.user_fields.email')" v-model="dialog.email.data.email" validate="email" />
				</ap-card-section>

				<ap-card-section class="row items-center justify-between">
					<ap-button v-if="dialog.email.sent" v-close-popup flat class="q-ml-auto" :label="$t('globals.close')" />

					<template v-else>
						<ap-button v-close-popup flat :label="$t('globals.cancel')" />
						<ap-button color="primary" :loading="dialog.email.loading" @click="change_email" :label="$t('globals.change')" />
					</template>
				</ap-card-section>
			</ap-dialog-card>
		</ap-dialog>
	</ap-page>
</template>

<script>
import { core } from 'src/core';
import { nextTick, onMounted, ref } from 'vue';

export default {
	setup() {
		const state = ref({
			loading: false,
			changed: false,

			data: {
				first_name: '',
				last_name: '',
				phone: '',
				birth_date: '',
			},
		});

		const dialog = ref({
			email: {
				open: false,
				loading: false,
				sent: true,

				data: {
					email: '',
				},
			},

			password: {
				open: false,
				loading: false,
				data: {
					password: '',
					password_confirmation: '',
				},
			},
		});

		onMounted(() => {
			state.value.data = {
				first_name: core.state.user.first_name,
				last_name: core.state.user.last_name,
				birth_date: core.state.user.birth_date,
				email: core.state.user.email,
				phone: core.state.user.phone,
			};

			nextTick(() => {
				state.value.changed = false;
			});
		});

		async function save() {
			state.value.loading = true;

			core.clearErrors();
			const response = await core.store.dispatch('user/update', state.value.data);

			if (response.status) {
				core.$q.notify({
					type: 'positive',
					message: core.locale.t('globals.saved'),
				});

				state.value.changed = false;
			} else if (response.statusCode == 422) {
				core.setErrors(response.errors);
			} else {
				core.$q.notify({
					type: 'negative',
					message: core.locale.t('globals.errors.unknown'),
				});
			}

			state.value.loading = false;
		}

		function open_password_dialog() {
			dialog.value.password.open = true;
		}

		function open_email_dialog() {
			dialog.value.email.open = true;
			dialog.value.email.sent = false;
			dialog.value.email.loading = false;
			dialog.value.email.data.email = null;
		}

		async function change_password() {
			dialog.value.password.loading = true;

			core.clearErrors();
			const response = await core.store.dispatch('user/change_password', dialog.value.password.data);

			if (response.status) {
				dialog.value.password.open = false;
				dialog.value.password.data = {
					password: '',
					password_confirmation: '',
				};

				core.$q.notify({
					type: 'positive',
					message: core.locale.t('globals.saved'),
				});
			} else if (response.statusCode == 422) {
				core.setErrors(response.errors);
			} else {
				core.$q.notify({
					type: 'negative',
					message: core.locale.t('globals.errors.unknown'),
				});
			}

			dialog.value.password.loading = false;
		}

		async function change_email() {
			dialog.value.password.loading = true;

			core.clearErrors();
			const response = await core.store.dispatch('user/change_email', dialog.value.email.data);

			if (response.status) {
				dialog.value.email.sent = true;
				dialog.value.email.data = {
					email: '',
				};
			} else if (response.statusCode == 422) {
				core.setErrors(response.errors);
			} else {
				core.$q.notify({
					type: 'negative',
					message: core.locale.t('globals.errors.unknown'),
				});
			}

			dialog.value.email.loading = false;
		}

		return {
			state,
			dialog,
			//
			save,
			open_password_dialog,
			open_email_dialog,
			change_password,
			change_email,
		};
	},
};
</script>
