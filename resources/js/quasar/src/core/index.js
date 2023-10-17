import { Router } from 'src/router';
import { Store } from 'src/store';
import { validation } from './validation';
import { user } from './user';
import { lists } from './lists';
import { locales } from './locales';
import { pagination } from './pagination';
import { misc } from './misc';
import config from 'app/project.config';

export const core = {
	$q: {},
	config,

	router: Router,
	store: Store,
	state: Store.state,

	user,
	lists,

	...locales,
	...validation,
	...pagination,
	...misc,
};
