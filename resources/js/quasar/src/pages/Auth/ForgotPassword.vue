<template>
	<ap-card flat>
		<ap-card-section class="row justify-center q-gap-sm">
			<span class="text-h5">{{ $t('pages.auth.forgot_pass.title') }}</span>
			<span v-if="!state.sent" class="text-subtitle text-center">{{ $t('pages.auth.forgot_pass.subtitle') }}</span>
		</ap-card-section>

		<ap-card-section class="column">
			<span v-if="state.sent" class="flex flex-center q-pa-lg text-center">{{ $t('pages.auth.forgot_pass.sent_notice') }}</span>

			<ap-input v-else v-model="state.data.email" type="email" :label="$t('globals.user_fields.email')" validate="email" @keypress.enter="send" />
		</ap-card-section>

		<ap-card-section class="row items-center justify-between">
			<ap-button flat :label="$t('globals.back')" :to="{ name: 'auth.login' }" />
			<ap-button v-if="!state.sent" color="primary" :label="$t('pages.auth.forgot_pass.send')" :loading="state.loading" @click="send" />
		</ap-card-section>
	</ap-card>
</template>

<script>
import { ref } from 'vue';
import { core } from 'src/core';

export default {
	setup() {
		const state = ref({
			loading: false,
			sent: false,

			data: {
				email: '',
			},
		});

		async function send() {
			state.value.loading = true;

			core.clearErrors();

			const response = await core.store.dispatch('user/forgot', state.value.data);

			if (response.status) {
				state.value.sent = true;
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
