import Alpine from 'alpinejs'
import datepicker from './components/datepicker'
import { disablePostTermLinks } from './post-terms-topics.js';

// AlpineJS
window.Alpine = Alpine
Alpine.start()
datepicker()

disablePostTermLinks();