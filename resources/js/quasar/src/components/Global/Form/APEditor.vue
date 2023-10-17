<template>
	<div>
		<q-editor ref="editor" v-model="value" :toolbar="toolbar" :class="{ 'q-editor--focused': isFocused, 'q-editor--no-borders': noBorders, 'q-editor--validation-error': validate && validation.error(validate) }" @paste="onPaste" />
		<ap-validation :validate="validate" />
	</div>
</template>

<script>
import { useQuasar } from 'quasar';
import { onUpdated, onMounted, onUnmounted, ref, watch } from 'vue';

export default {
	props: {
		modelValue: {
			type: String,
			default: '',
		},

		rich: {
			type: Boolean,
			default: false,
		},

		noToolbar: {
			type: Boolean,
			default: false,
		},

		noBorders: {
			type: Boolean,
			default: false,
		},

		validate: {
			type: String,
			default: null,
		},

		processPaste: {
			type: Boolean,
			default: false,
		},

		noFocus: {
			type: Boolean,
			default: false,
		},
	},

	setup(props, { emit }) {
		const $q = useQuasar();
		const editor = ref(null);
		const value = ref(props.modelValue);
		const isFocused = ref(false);

		const toolbar = props.noToolbar
			? []
			: props.rich
			? [
					[
						{
							label: $q.lang.editor.align,
							icon: $q.iconSet.editor.align,
							fixedLabel: true,
							options: ['left', 'center', 'right', 'justify'],
						},
					],
					['bold', 'italic', 'strike', 'underline'],
					// ['token', 'hr', 'link', 'custom_btn'],
					// ['fullscreen'],
					[
						{
							label: $q.lang.editor.formatting,
							icon: $q.iconSet.editor.formatting,
							list: 'no-icons',
							options: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'code'],
						},
						{
							label: $q.lang.editor.fontSize,
							icon: $q.iconSet.editor.fontSize,
							fixedLabel: true,
							fixedIcon: true,
							list: 'no-icons',
							options: ['size-1', 'size-2', 'size-3', 'size-4', 'size-5', 'size-6', 'size-7'],
						},
						'removeFormat',
					],

					['unordered', 'ordered'],
					// ['undo', 'redo'],
					['viewsource'],
			  ]
			: [
					['bold', 'italic', 'underline', 'strike'],
					['unordered', 'ordered'],
					['undo', 'redo'],
			  ];
		//

		watch(value, function (newValue) {
			if (props.processPaste) {
				newValue = newValue.replace(/<(?!\/?(b|i|u|strike|ul|ol|li)\b)[^>]+>/gi, '');
			}
			emit('update:modelValue', newValue);
		});

		onUpdated(() => {
			value.value = props.modelValue;
		});

		onMounted(() => {
			let el = editor.value?.caret?.el;
			if (el) {
				el.addEventListener('focus', onFocus);
				el.addEventListener('blur', onBlur);
			}
		});

		onUnmounted(() => {
			let el = editor.value?.caret?.el;
			if (el) {
				el.removeEventListener('focus', onFocus);
				el.removeEventListener('blur', onBlur);
			}
		});

		function onFocus() {
			if (!props.noFocus) {
				isFocused.value = true;
			}
		}

		function onBlur() {
			if (!props.noFocus) {
				isFocused.value = false;
			}
		}

		function onPaste(evt) {
			if (props.processPaste) {
				if (evt.target.nodeName === 'INPUT') return;

				let text, onPasteStripFormattingIEPaste;

				evt.preventDefault();
				evt.stopPropagation();

				if (evt.originalEvent && evt.originalEvent.clipboardData.getData) {
					text = evt.originalEvent.clipboardData.getData('text/plain');
					text = text.replace(/(<([^>]+)>)/gi, '');
					editor.value.runCmd('insertText', text);
				} else if (evt.clipboardData && evt.clipboardData.getData) {
					text = evt.clipboardData.getData('text/plain');
					text = text.replace(/(<([^>]+)>)/gi, '');
					editor.value.runCmd('insertText', text);
				} else if (window.clipboardData && window.clipboardData.getData) {
					if (!onPasteStripFormattingIEPaste) {
						onPasteStripFormattingIEPaste = true;
						editor.value.runCmd('ms-pasteTextOnly', text);
					}
					onPasteStripFormattingIEPaste = false;
				}
			}
		}

		return {
			toolbar,
			value,
			editor,
			isFocused,
			onPaste,
		};
	},
};
</script>

<style lang="scss">
.q-editor {
	position: relative;
	border: none !important;

	&--no-borders {
		&::after {
			display: none !important;
		}
	}

	&:after {
		content: '';
		position: absolute;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		pointer-events: none;

		border: 1px solid rgba(black, 0.24);
		border-radius: 4px;
		transition: border-color 0.36s cubic-bezier(0.4, 0, 0.2, 1);
	}

	&--focused {
		&:after {
			border: 2px solid $primary !important;
		}
	}

	&--validation-error {
		&:after {
			border: 2px solid $negative !important;
		}
	}
}
</style>
