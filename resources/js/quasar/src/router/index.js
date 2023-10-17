import { route } from 'quasar/wrappers';
import { createRouter, createMemoryHistory, createWebHistory, createWebHashHistory } from 'vue-router';
import routes from './routes';
import { Store } from 'src/store';
import { core } from 'src/core';
import config from 'app/project.config';

const createHistory = process.env.SERVER ? createMemoryHistory : process.env.VUE_ROUTER_MODE === 'history' ? createWebHistory : createWebHashHistory;

const Router = createRouter({
	scrollBehavior: () => ({ left: 0, top: 0 }),
	routes: routes,
	history: createHistory(process.env.VUE_ROUTER_BASE),
});

Router.protectRoute = function (route = null, next = null) {
	// * If app is not loaded - do nothing
	if (!Store.state.app.loaded) return;

	// * If route is not given - try to resolve current route
	route = route || Router.resolve(window.location.pathname + window.location.search);

	// * If route exists
	if (route) {
		const user = Store.state.user;
		const is_logged_in = user?.is_logged_in;

		// * If user is logged in - check if route is not auth route like /login /forgot etc.
		if (is_logged_in) {
			if (route?.meta?.is_auth || route.matched?.some((record) => record?.meta?.is_auth)) {
				// * redirect to home
				return next ? next({ name: config.app.home }) : Router.replace({ name: config.app.home });
			}
		} else {
			// * If user is not logged in - check if route is protected
			if (route?.meta?.auth || route.matched?.some((record) => record?.meta?.auth)) {
				// * redirect to login
				return next ? next({ name: 'auth.login' }) : Router.replace({ name: 'auth.login' });
			}
		}
	}

	if (next) {
		next();
	} else {
		Router.replace(route);
	}
};

export default route(function ({ store, ssrContext }) {
	Router.beforeEach((to, from, next) => {
		core.clearErrors();
		return Router.protectRoute(to, next);
	});

	return Router;
});

export { Router };
