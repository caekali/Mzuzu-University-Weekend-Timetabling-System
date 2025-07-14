import './bootstrap';

import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';

import Tooltip from '@ryangjchandler/alpine-tooltip';


import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';


Alpine.plugin(Tooltip);
Livewire.start()