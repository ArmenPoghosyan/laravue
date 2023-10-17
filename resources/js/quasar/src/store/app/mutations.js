export function set_loaded(state, value) {
	state.loaded = value;
}

export function password_popup(state, value) {
	state.password_popup = { ...state.password_popup, ...value };
}

export function media_popup(state, value) {
	state.media_popup = { ...state.media_popup, ...value };
}
