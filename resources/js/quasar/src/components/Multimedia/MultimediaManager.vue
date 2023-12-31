<template>
	<ap-card>
		<ap-card-section v-if="header" class="row items-center relative-position">
			<span class="text-h6 q-mr-auto">{{ title }}</span>

			<ap-button unelevated color="primary" size="10px" :label="$t('globals.add')">
				<ap-menu v-if="limit == 0 || state.list.length < limit" auto-close cover anchor="top right">
					<ap-list separator>
						<ap-item v-if="photo" clickable @click="type_selected('photo')">
							<ap-item-section icon>
								<ap-icon name="sym_o_image" size="24px" />
							</ap-item-section>

							<ap-item-section>
								<ap-item-label>{{ $t('globals.multimedia_types.photo') }}</ap-item-label>
							</ap-item-section>
						</ap-item>

						<ap-item v-if="video" clickable @click="type_selected('video')">
							<ap-item-section icon>
								<ap-icon name="sym_o_smart_display" size="24px" />
							</ap-item-section>

							<ap-item-section>
								<ap-item-label>{{ $t('globals.multimedia_types.video') }}</ap-item-label>
							</ap-item-section>
						</ap-item>

						<ap-item v-if="link" clickable @click="type_selected('link')">
							<ap-item-section icon>
								<ap-icon name="sym_o_youtube_activity" size="24px" />
							</ap-item-section>

							<ap-item-section>
								<ap-item-label>Youtube</ap-item-label>
							</ap-item-section>
						</ap-item>
					</ap-list>
				</ap-menu>

				<ap-tooltip v-else :offset="[0, 28]" anchor="top right" self="top middle" class="text-white bg-negative text-no-wrap">{{ $t('globals.errors.photo_limit_reached') }}</ap-tooltip>
			</ap-button>
		</ap-card-section>

		<ap-separator />

		<ap-card-section no-padding>
			<template v-if="state.list.length">
				<ap-list separator>
					<draggable
						v-bind="{ animation: 200 }"
						v-model="state.list"
						item-key="order"
						:component-data="{
							name: 'flip-list',
						}"
						@sort="update_state"
					>
						<template #item="{ element, index }">
							<ap-item :key="element.order" :class="{ 'bordered-bottom bordered-light': index < state.list.length - 1 }">
								<ap-item-section icon>
									<ap-icon name="sym_o_menu" size="20px" color="grey-5" class="cursor-pointer" />
								</ap-item-section>

								<ap-item-section>
									<div class="row items-center q-gap-sm">
										<div class="rounded-4 bordered bordered-light flex flex-center relative-position" style="width: 40px; height: 40px">
											<img
												v-if="(element.thumbnail || element.type == 'photo') && element.path"
												:src="element.thumbnail || (element.type == 'photo' && media(element.path, 's'))"
												class="fit rounded-4 cursor-pointer"
												style="object-fit: cover"
												@click.prevent.stop="open_media_popup(media(element.path))"
											/>

											<ap-icon v-else-if="element.type == 'video'" name="sym_o_smart_display" size="24px" class="text-primary" />
											<ap-icon v-else-if="element.type == 'link'" name="sym_o_youtube_activity" size="24px" class="text-primary" />

											<div v-if="!element?.id" class="absolute-full overlay-color overlay-top flex flex-center">
												<ap-circular-progress :indeterminate="element?.percent == 100 && element?.id == null" size="20px" track-color="black" color="white" :value="element?.percent" />
												<ap-tooltip anchor="top middle" self="top middle">{{ element?.percent }}%</ap-tooltip>
											</div>
										</div>

										<span class="text-body2">
											<template v-if="element.type == 'link'">Youtube</template>
											<template v-else>{{ $t(`globals.multimedia_types.${element.type}`) }}</template>
										</span>

										<template v-if="element?.id">
											<a class="text-no-underline" target="_blank" :href="element.type == 'link' ? element.path : media(element.path)">
												<ap-icon name="sym_o_open_in_new" size="20px" color="grey-5" />
											</a>

											<ap-button round flat size="10px" icon="more_vert" class="q-ml-auto">
												<ap-menu auto-close cover anchor="top right">
													<ap-list separator>
														<ap-item clickable @click="delete_media(index)">
															<ap-item-section icon>
																<ap-icon name="sym_o_delete" color="negative" size="20px" />
															</ap-item-section>

															<ap-item-section>
																<ap-item-label>{{ $t('globals.delete') }}</ap-item-label>
															</ap-item-section>
														</ap-item>
													</ap-list>
												</ap-menu>
											</ap-button>
										</template>
									</div>
								</ap-item-section>
							</ap-item>
						</template>
					</draggable>
				</ap-list>
			</template>

			<template v-else>
				<div class="column q-gap-sm flex flex-center q-px-md q-py-lg">
					<ap-icon name="sym_o_subtitles" size="60px" class="text-grey-4" />
					<span class="text-body2 text-grey-6">{{ $t('globals.nothing_here') }}</span>
				</div>
			</template>
		</ap-card-section>

		<input ref="input_ref" type="file" class="hidden" @change="file_selected" />
	</ap-card>
</template>

<script>
import { core } from 'src/core';
import { ref } from 'vue';
import { file_utils } from 'src/core/file_utils';

import draggable from 'vuedraggable';

export default {
	components: { draggable },
	emits: ['update:modelValue', 'update:loading'],
	props: {
		modelValue: {
			type: Array,
			default: () => [],
		},

		loading: {
			type: Boolean,
			default: false,
		},

		header: {
			type: Boolean,
			default: true,
		},

		title: {
			type: String,
			default: core.locale.t('globals.multimedia'),
		},

		limit: {
			type: Number,
			default: 0,
		},

		photo: {
			type: Boolean,
			default: false,
		},

		video: {
			type: Boolean,
			default: false,
		},

		link: {
			type: Boolean,
			default: false,
		},
	},

	setup(props, { emit }) {
		const input_ref = ref(null);

		const state = ref({
			list: [...props.modelValue] || [],
			link_selected: false,
		});

		function type_selected(type) {
			switch (type) {
				case 'photo':
				case 'video':
					browse_files(type);
					break;

				case 'link':
					core.$q
						.dialog({
							title: core.locale.t('globals.multimedia_types.link'),
							prompt: {
								model: '',
								type: 'url',
								isValid: (val) => {
									let regex = /^(http(s)??\:\/\/)?(www\.)?((youtube\.com\/watch\?v=)|(youtu.be\/))([a-zA-Z0-9\-_])+/;

									return regex.test(val);
								},
							},
							cancel: core.locale.t('globals.cancel'),
							persistent: true,
						})
						.onOk(async (url) => {
							let index = state.value.list.push({
								id: null,
								type: 'link',
								path: url,
								percent: 100,
							});

							let instance = state.value.list[index - 1];
							instance.order = index;

							const response = await core.store.dispatch('multimedia/store', instance);

							if (response.status) {
								instance.id = response.multimedia.id;
								instance.path = response.multimedia.path;
							}

							update_state();
						});
					break;
			}
		}

		function browse_files(type) {
			if (input_ref.value) {
				switch (type) {
					case 'photo':
						input_ref.value.setAttribute('accept', 'image/*');
						break;

					case 'video':
						input_ref.value.setAttribute('accept', 'video/*');
						break;

					default:
						input_ref.value.setAttribute('accept', '*');
						break;
				}

				input_ref.value.value = null;

				input_ref.value?.click();
			}
		}

		function file_selected(event) {
			const MAX_FILE_SIZE = 20; // 20MB
			let file = event.target.files[0];

			let type = null;

			// Determine the type of the file
			if (file.type.indexOf('image') > -1) {
				type = 'photo';
			} else if (file.type.indexOf('video') > -1) {
				type = 'video';
			}

			// Check if the file is valid
			if (['photo', 'video'].indexOf(type) == -1) {
				core.$q.notify({
					type: 'negative',
					message: core.locale.t('globals.errors.invalid_file_type'),
				});

				return;
			}

			if (type == 'photo' && ['jpg', 'jpeg', 'png', 'bmp'].indexOf(file.name.split('.').pop().toLowerCase()) == -1) {
				core.$q.notify({
					type: 'negative',
					message: core.locale.t('globals.errors.invalid_file_type'),
				});

				return;
			}

			// Check if the file is too large
			if (file.size > MAX_FILE_SIZE * 1024 * 1024) {
				core.$q.notify({
					type: 'negative',
					message: core.locale.t('globals.errors.file_too_large', { size: MAX_FILE_SIZE }),
				});

				return;
			}

			let instance = {
				id: null,
				type,
				file,
				thumbnail: null,
				percent: 0,
			};

			state.value.list.push(instance);

			let index = state.value.list.length - 1;
			instance = state.value.list[index];
			instance.order = index;

			process_media(index);

			switch (type) {
				case 'photo':
					file_utils.get_photo_thumbnail(file).then((thumbnail) => {
						instance.thumbnail = thumbnail;
					});
					break;

				case 'video':
					file_utils.get_video_thumbnail(file).then((thumbnail) => {
						instance.thumbnail = thumbnail;
					});
					break;
			}
		}

		async function process_media(index) {
			const media_instance = state.value.list[index];

			emit_loading();

			const response = await core.store.dispatch('multimedia/store', {
				...media_instance,
				config: {
					onUploadProgress: (event) => {
						media_instance.percent = Math.round((event.loaded * 100) / event.total);
					},
				},
			});

			if (response.status) {
				media_instance.id = response.multimedia.id;
				media_instance.path = response.multimedia.path;
				update_state();
				emit_loading();
				//
			} else {
				state.value.list.splice(index, 1);

				let message = response?.message || response?.errors?.file?.[0] || core.locale.t('globals.errors.unknown');
				core.$q.notify({
					type: 'negative',
					message,
				});
			}
		}

		function delete_media(index) {
			let media = state.value.list[index];

			if (media) {
				core.$q
					.dialog({
						title: core.locale.t('globals.confirm'),
						message: core.locale.t('globals.deletion_notice'),
						ok: core.locale.t('globals.yes'),
						cancel: core.locale.t('globals.no'),
					})
					.onOk(async () => {
						const response = await core.store.dispatch('multimedia/destroy', media.id);

						if (response) {
							state.value.list.splice(index, 1);
						} else {
							core.$q.notify({
								type: 'negative',
								message: core.locale.t('globals.errors.unknown'),
							});
						}
					});
				//
			}
		}

		function update_state() {
			emit_state();

			// let data = state.value.list
			// 	.map((item, index) => {
			// 		return {
			// 			id: item.id,
			// 			order: index,
			// 		};
			// 	})
			// 	.filter((item) => item.id != null);
			// //
			// core.store.dispatch('multimedia/reorder', { order: data });
		}

		function emit_state() {
			emit(
				'update:modelValue',
				state.value.list
					.filter((item) => item.id != null)
					.map((item, index) => {
						return {
							id: item.id,
							type: item.type,
							path: item.path,
							order: index,
						};
					})
			);
		}

		function emit_loading() {
			emit(
				'update:loading',
				state.value.list.some((item) => item.id == null)
			);
		}

		return {
			state,
			input_ref,
			//
			browse_files,
			file_selected,
			type_selected,
			update_state,

			//
			delete_media,
		};
	},
};
</script>
