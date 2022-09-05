import * as sapper from '@sapper/app';

import { startClient } from './i18n.js';

await startClient();

sapper.start({
	target: document.querySelector('#sapper'),
});