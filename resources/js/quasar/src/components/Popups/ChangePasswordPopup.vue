<template>
	<ap-dialog v-model="state.open" persistent>
		<ap-dialog-card style="max-width: 500px">
			<ap-dialog-header>
				<span class="text-h6 q-mr-auto">{{ $t('globals.user_fields.change_password') }}</span>
			</ap-dialog-header>

			<ap-card-section>
				<div class="row q-col-gutter-md">
					<ap-input type="password" class="col-xs-12" v-model="state.data.password" @keypress.enter="change_password" :label="$t('globals.user_fields.password')" validate="password" />
					<ap-input type="password" class="col-xs-12" v-model="state.data.password_confirmation" @keypress.enter="change_password" :label="$t('globals.user_fields.password_confirm')" validate="password_confirmation" />
				</div>
			</ap-card-section>

			<ap-card-section class="row justify-end">
				<!-- <ap-button v-close-popup flat :label="$t('globals.cancel')" /> -->
				<ap-button color="primary" :label="$t('globals.change')" :loading="state.loading" @click="change_password" />
			</ap-card-section>
		</ap-dialog-card>
	</ap-dialog>
</template>

<script>
import { core } from 'src/core';
import { ref } from 'vue';

export default {
	setup() {
		const state = ref({
			loading: false,
			open: true,

			data: {
				password: '',
				password_confirmation: '',
			},
		});

		async function change_password() {
			state.value.loading = true;

			core.clearErrors();
			const response = await core.store.dispatch('user/change_password', state.value.data);

			if (response.status) {
				state.value.open = false;
				state.value.data.password = '';
				state.value.data.password_confirmation = '';
			} else if (response.statusCode == 422) {
				core.setErrors(response.errors);
			} else {
				core.error(response?.message || core.label('globals.errors.unknown'));
			}

			state.value.loading = false;
		}

		return {
			state,
			//
			change_password,
		};
	},
};
</script>
