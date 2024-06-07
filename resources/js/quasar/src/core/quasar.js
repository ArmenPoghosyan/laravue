import { Dialog, Notify } from 'quasar';

export const quasar = {
	/**
	 * Creates a dialog using the provided options.
	 *
	 * @param {import('quasar').QDialogOptions} options
	 * @returns {import('quasar').DialogChainObject}
	 */
	dialog(options) {
		return Dialog.create(options);
	},

	/**
	 * Creates a notification using the provided options.
	 *
	 * @param {import('quasar').QNotifyCreateOptions} options - The options for the notification.
	 * @returns
	 */
	notify(options) {
		return Notify.create(options);
	},

	/**
	 * Creates a notification with the color set to 'negative'.
	 *
	 * @param {*} message
	 */
	error(message) {
		this.notify({
			color: 'negative',
			message,
		});
	},

	/**
	 * Creates a notification with the color set to 'positive'.
	 *
	 * @param {*} message
	 */
	success(message) {
		this.notify({
			color: 'positive',
			message,
		});
	},

	/**
	 * Creates a notification with the color set to 'warning'.
	 *
	 * @param {*} message
	 */
	warning(message) {
		this.notify({
			color: 'warning',
			message,
		});
	},
};
