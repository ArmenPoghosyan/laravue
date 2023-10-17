import { store } from 'quasar/wrappers';
import { createStore } from 'vuex';

import app from './app';
import user from './user';

import localization from './localization';
import multimedia from './multimedia';

const Store = createStore({
	modules: {
		app,
		user,
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
