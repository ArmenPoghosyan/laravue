import { core } from '.';

export const user = {
	async logout() {
		const response = await core.store.dispatch('user/logout');
		core.router.protectRoute();
	},
};
