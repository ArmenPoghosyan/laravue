<template>
	<ap-dialog v-model="is_open">
		<ap-dialog-card style="max-width: 500px">
			<ap-dialog-header>
				<span class="text-h6 q-mr-auto">{{ $t('globals.popups.password.title') }}</span>

				<ap-button v-close-popup round flat icon="close" size="12px" />
			</ap-dialog-header>

			<ap-card-section class="text-white bg-negative q-pa-md">
				{{ $t('globals.popups.password.description') }}
			</ap-card-section>

			<ap-card-section>
				<ap-input type="password" v-model="state.data.password" @keypress.enter="check" :label="$t('globals.user_fields.password')" validate="password" />
			</ap-card-section>

			<ap-card-section class="row justify-between">
				<ap-button v-close-popup flat :label="$t('globals.cancel')" />
				<ap-button color="primary" :label="$t('globals.continue')" :loading="state.loading" @click="check" />
			</ap-card-section>
		</ap-dialog-card>
	</ap-dialog>
</template>

<script>
import { core } from 'src/core';
import { computed, ref } from 'vue';

export default {
	setup() {
		const is_open = computed({
			get: () => core.store.state.app.password_popup.open,
			set: (value) => core.store.commit('app/password_popup', { open: value }),
		});

		const state = ref({
			loading: false,

			data: {
				password: '',
			},
		});

		async function check() {
			state.value.loading = true;

			core.clearErrors();
			const response = await core.store.dispatch('user/check_password', state.value.data);

			if (response.status) {
				is_open.value = false;
				state.value.data.password = '';

				let fn = core.state.app.password_popup?.fn;
				if (fn && typeof fn == 'function') {
					fn();
				}

				core.store.commit('app/password_popup', { open: false, fn: null });
			} else if (response.statusCode == 422) {
				core.setErrors(response.errors);
			} else {
				core.$q.notify({
					type: 'negative',
					message: core.$t('globals.errors.unknown'),
				});
			}

			state.value.loading = false;
		}

		return {
			state,
			is_open,
			//
			check,
		};
	},
};
</script>
