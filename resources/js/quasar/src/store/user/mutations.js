export function set(state, data) {
	for (let key in data) {
		state[key] = data[key];
	}
}

export function clean(state) {
	for (let key in state) {
		state[key] = null;
	}

	state.is_logged_in = false;
}

export function set_logged_in(state, value) {
	state.is_logged_in = value;
}
