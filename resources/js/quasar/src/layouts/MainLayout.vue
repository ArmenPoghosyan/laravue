<template>
	<ap-layout view="lHh Lpr lFf">
		<ap-header elevated class="row items-center q-gap-sm">
			<ap-button flat round icon="menu" aria-label="Menu" @click="toggleDrawer" />

			<ap-link class="text-h6 text-white text-no-underline">{{ config.app.name }}</ap-link>

			<ap-button round flat class="q-ml-auto">
				<ap-icon name="sym_o_account_circle" />

				<ap-menu>
					<ap-card :rounded="false">
						<ap-card-section class="row no-wrap items-center q-gap-sm">
							<ap-icon name="sym_o_account_circle" size="32px" />

							<div class="column">
								<span class="text-caption2 text-no-wrap">{{ $user.full_name }}</span>
							</div>
						</ap-card-section>

						<ap-separator />

						<ap-card-section no-padding>
							<ap-list separator>
								<ap-item clickable @click="logout">
									<ap-item-section icon>
										<ap-icon name="logout" size="24px" />
									</ap-item-section>

									<ap-item-section>
										<ap-item-label>Logout</ap-item-label>
									</ap-item-section>
								</ap-item>
							</ap-list>
						</ap-card-section>
					</ap-card>
				</ap-menu>
			</ap-button>
		</ap-header>

		<ap-drawer v-model="drawer" overlay behavior="mobile" bordered>
			<ap-card flat class="fit column no-wrap">
				<ap-card-section class="row items-center justify-between">
					<ap-link class="text-no-underline text-secondary text-h6" :to="{ name: config.app.home }">
						{{ config.app.name }}
					</ap-link>

					<ap-button flat round icon="close" size="sm" @click="drawer = false" />
				</ap-card-section>

				<ap-separator />

				<ap-card-section no-padding class="full-height">
					<ap-list separator>
						<ap-item clickable exact :to="{ name: 'localizations.index' }" active-class="bg-grey-3 text-primary">
							<ap-item-section icon>
								<ap-icon name="translate" size="24px" />
							</ap-item-section>

							<ap-item-section>
								<ap-item-label>{{ $t('pages.localizations.title') }}</ap-item-label>
							</ap-item-section>
						</ap-item>
					</ap-list>
				</ap-card-section>

				<ap-separator />

				<ap-card-section>
					<!-- Language -->
					<ap-button rounded flat class="q-ml-auto header__actionButton justify-self: start" icon="language" size="14px" :label="current_locale.toUpperCase()" v-if="lists.languages.length > 1">
						<ap-menu transition-show="jump-down" transition-hide="jump-up" anchor="top left" self="bottom left" :offset="[0, 8]">
							<ap-list separator>
								<ap-item v-for="(language, index) in lists.languages" :key="index" v-ripple clickable @click="set_locale(language.value)" :active="language.value == current_locale" active-class="bg-grey-3">
									<!-- <ap-item-section avatar icon>
										<img :src="language.flag" :alt="language.label" style="width: 24px" />
									</ap-item-section> -->

									<ap-item-section> {{ language.label }} </ap-item-section>
								</ap-item>
							</ap-list>
						</ap-menu>
					</ap-button>
					<!-- Language -->
				</ap-card-section>
			</ap-card>
		</ap-drawer>

		<ap-page-container>
			<router-view />
		</ap-page-container>
	</ap-layout>
</template>

<script>
import { defineComponent, ref } from 'vue';

export default defineComponent({
	name: 'MainLayout',

	setup() {
		const drawer = ref(false);

		function toggleDrawer() {
			drawer.value = !drawer.value;
		}

		return {
			drawer,
			toggleDrawer,
		};
	},
});
</script>
