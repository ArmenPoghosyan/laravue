<template>
	<ap-page>
		<ap-card v-if="state.loaded">
			<ap-card-section :horizontal="$q.screen.gt.xs" no-padding class="q-gap-lg items-start overflow-hidden">
				<ap-card-section class="flex-shrink column flex-center q-my-lg q-gap-md" :class="{ 'q-mx-xl q-pa-none': $q.screen.gt.xs, 'q-px-sm': $q.screen.lt.sm }">
					<ap-avatar bordered size="100px">
						<ap-img v-if="state.user.avatar" :src="media(state.user.avatar, 's')" fit="cover" class="fit cursor-pointer" @click.stop.prevent="open_media_popup(media(state.user.avatar))" />
						<ap-icon v-else name="sym_o_person" color="grey-6" />
					</ap-avatar>

					<div class="column items-center no-wrap text-center">
						<span class="text-h6">{{ state.user.full_name }}</span>
					</div>

					<div class="row no-wrap q-gap-lg">
						<!--  -->
					</div>

					<span v-if="state.user.type != 'admin' && state.user.id != $user.id" class="row items-center q-gap-sm text-weight-bold cursor-pointer">
						<ap-button round size="xs" icon="sym_o_edit" />
						<span v-if="state.user.status == 'active'" class="text-positive">{{ $t('globals.active') }}</span>
						<span v-if="state.user.status == 'archived'" class="text-negative">{{ $t('globals.archived') }}</span>

						<q-popup-edit v-model="state.user.status" v-slot="scope" class="q-pa-md">
							<div class="column q-gap-md">
								<ap-select v-model="scope.value" :options="lists.user_statuses.value" map-options emit-value :label="$t('globals.status')" style="min-width: 200px" />

								<div class="row items-center q-gap-md">
									<ap-button v-close-popup flat :label="$t('globals.close')" class="q-mr-auto" />
									<ap-button v-close-popup color="primary" :label="$t('globals.save')" :loading="state.status_loading" @click="update_status(scope.value)" />
								</div>
							</div>
						</q-popup-edit>
					</span>
				</ap-card-section>

				<ap-card-section class="q-py-none full-width overflow-hidden" no-padding>
					<!-- <ap-tabs outside-arrows mouse-scroll v-model="state.page" inline-label active-color="primary" align="left">
						<ap-route-tab :to="{ name: 'user.index' }" name="info" icon="sym_o_person" :label="$t('pages.users.info')" />
					</ap-tabs> -->

					<!-- <ap-separator /> -->

					<div class="column full-width" :class="{ 'q-py-sm': $q.screen.gt.xs, 'q-pa-md': $q.screen.lt.sm }">
						<Info v-if="state.page == 'info'" v-model="state.user" />
					</div>
				</ap-card-section>
			</ap-card-section>
		</ap-card>

		<ap-page-loader v-else />
	</ap-page>
</template>

<script>
import { core } from 'src/core';
import { ref, onMounted } from 'vue';

import { Info } from './Parts';

export default {
	components: { Info },
	setup() {
		const state = ref({
			is_adding: false,
			loaded: false,
			loading: false,
			disabled: false,

			status_loading: false,
			page: 'info',

			user: {
				id: null,
			},
		});

		onMounted(() => {
			const id = core.router.currentRoute.value.params?.id;

			if (id) {
				fetch(id);
			}
		});

		async function fetch(id) {
			state.value.loading = true;

			const response = await core.store.dispatch(`users/show`, { id });

			if (response.status) {
				state.value.user = response.user;
			} else {
				core.$q.notify({
					color: 'negative',
					message: core.locale.t('globals.errors.unknown'),
				});
			}

			state.value.loaded = true;
			state.value.loading = false;
		}

		async function save() {
			state.value.loading = true;

			core.clearErrors();

			let response = null;
			if (state.value.is_adding) {
				response = await core.store.dispatch(`${base_store}/store`, state.value.data);
			} else {
				response = await core.store.dispatch(`${base_store}/update`, state.value.data);
			}

			if (response.status) {
				done();
			} else if (response.statusCode == 422) {
				if (response.errors?.content?.length) {
					core.$q.notify({
						color: 'negative',
						message: response.errors.content[0],
					});
				}

				core.setErrors(response.errors);
			} else {
				core.$q.notify({
					color: 'negative',
					message: core.locale.t('globals.errors.unknown'),
				});
			}

			state.value.loading = false;
		}

		async function update_status(value) {
			state.value.status_loading = true;

			const response = await core.store.dispatch('users/update_status', {
				id: state.value.user.id,
				status: value,
			});

			if (response.status) {
				state.value.user.status = value;
			}

			state.value.status_loading = false;
		}

		return {
			state,
			//
			save,
			update_status,
		};
	},
};
</script>
