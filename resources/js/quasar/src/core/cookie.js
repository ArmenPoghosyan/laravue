export const cookie = {
	by_name(name) {
		let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
		if (match) return match[2];
	},
};
