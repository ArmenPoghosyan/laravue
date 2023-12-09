import { boot } from 'quasar/wrappers';
import { core } from 'src/core';

export default boot(async ({ app }) => {
	app.directive('password', {
		created: (el, props) => {
			el.addEventListener('click', function (e) {
				if (core.state.user.is_logged_in) {
					let fn = props.value;

					if (fn) {
						core.store.commit('app/password_popup', { open: true, fn });

						e.preventDefault();
						e.stopPropagation();
						return false;
					}
				}
			});
		},
	});
});
