import { store } from 'quasar/wrappers';
import { createStore } from 'vuex';

import app from './app';
import user from './user';

import localization from './localization';
import multimedia from './multimedia';
import users from './users';

/**
 * @type {import('vuex').Store}
 */
const Store = createStore({
	modules: {
		app,
		user,
		users,
		multimedia,
		localization,
	},

	// enable strict mode (adds overhead!)
	// for dev mode and --debug builds only
	strict: process.env.DEBUGGING,
});

export default store(function (/* { ssrContext } */) {
	return Store;
});

export { Store };
