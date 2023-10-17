import kebabCase from 'lodash/kebabCase';

export default async ({ app }) => {
	const components = import.meta.globEager('/src/components/Global/**/*.vue');

	Object.entries(components).forEach(([path, definition]) => {
		const componentName = kebabCase(
			path
				.split('/')
				.pop()
				.replace(/\.\w+$/, '')
		);

		app.component(componentName, definition.default);
	});
};
