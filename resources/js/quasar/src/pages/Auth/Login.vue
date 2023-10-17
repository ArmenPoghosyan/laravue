<template>
	<ap-card flat>
		<ap-card-section class="row justify-center">
			<span class="text-h5">{{ config.app.name }}</span>
		</ap-card-section>

		<transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
			<ap-card-section v-if="state.validation != null">
				<div class="full-width rounded-12 bg-negative text-white q-pa-md">{{ state.validation }}</div>
			</ap-card-section>
		</transition>

		<ap-card-section class="column q-gap-md">
			<ap-input v-model="state.data.email" type="email" :label="$t('globals.user_fields.email')" validate="email" @keypress.enter="login" />
			<ap-input v-model="state.data.password" type="password" :label="$t('globals.user_fields.password')" validate="password" @keypress.enter="login" />

			<div class="row items-center q-gap-md">
				<ap-checkbox dense v-model="state.data.remember" :label="$t('pages.auth.login.remember')" class="q-mr-auto" />
				<ap-link :to="{ name: 'auth.forgot' }">{{ $t('pages.auth.login.forgot_password') }}</ap-link>
			</div>
		</ap-card-section>

		<ap-card-section class="row items-center justify-end">
			<ap-button color="primary" :label="$t('pages.auth.login.login')" :loading="state.loading" @click="login" />
		</ap-card-section>
	</ap-card>
</template>

<script>
import { ref } from 'vue';
import { core } from 'src/core';
import config from 'app/project.config';

export default {
	setup() {
		const state = ref({
			loading: false,
			validation: null,
			data: {
				email: '',
				password: '',
				remember: true,
			},
		});

		async function login() {
			state.value.loading = true;
			state.value.validation = null;

			core.clearErrors();

			const response = await core.store.dispatch('user/login', state.value.data);

			if (response.status) {
				core.router.push({ name: config.app.home });
			} else if (response.statusCode == 422) {
				if (response.errors) {
					core.setErrors(response.errors);
				} else {
					state.value.validation = response?.message;
				}
			}

			state.value.loading = false;
		}

		return {
			state,
			login,
		};
	},
};
</script>
