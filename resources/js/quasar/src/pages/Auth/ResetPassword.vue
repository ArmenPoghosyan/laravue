<template>
	<ap-card flat>
		<ap-card-section class="column items-center q-gap-sm">
			<span class="text-h5">{{ $t('pages.auth.reset_pass.title') }}</span>
			<span class="text-subtitle text-center">{{ $t('pages.auth.reset_pass.subtitle') }}</span>
		</ap-card-section>

		<ap-card-section class="column q-gap-md">
			<ap-input v-model="state.data.password" type="password" :label="$t('globals.user_fields.password')" validate="password" @keypress.enter="send" />
			<ap-input v-model="state.data.password_confirmation" type="password" :label="$t('globals.user_fields.password_confirm')" validate="globals.user_fields.password_confirm" @keypress.enter="send" />
		</ap-card-section>

		<ap-card-section class="row items-center justify-between">
			<ap-button flat :label="$t('globals.back')" :to="{ name: 'auth.login' }" />
			<ap-button color="primary" :label="$t('pages.auth.reset_pass.reset')" :loading="state.loading" @click="send" />
		</ap-card-section>
	</ap-card>
</template>

<script>
import { onMounted, ref } from 'vue';
import { core } from 'src/core';

export default {
	setup() {
		const state = ref({
			loading: false,

			data: {
				token: null,
				email: '',
				password: '',
				password_confirmation: '',
			},
		});

		onMounted(() => {
			state.value.data.token = core.router.currentRoute.value.params?.token || null;
			state.value.data.email = core.router.currentRoute.value.query?.email || null;

			if (!state.value.data.token || !state.value.data.email) {
				core.router.push({ name: 'auth.login' });
			}
		});

		async function send() {
			state.value.loading = true;

			core.clearErrors();

			const response = await core.store.dispatch('user/reset', state.value.data);

			if (response.status) {
				core.router.push({ name: 'auth.login' });
				core.$q.notify({
					type: 'positive',
					message: $t('pages.auth.reset_pass.success'),
				});
			} else if (response.statusCode == 422) {
				core.setErrors(response.errors);
			}

			state.value.loading = false;
		}

		return {
			state,
			send,
		};
	},
};
</script>
