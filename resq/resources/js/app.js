import './bootstrap';

import Alpine from 'alpinejs';
import { marked } from 'marked';
import DOMPurify from 'dompurify';

window.Alpine = Alpine;
window.marked = marked;
window.DOMPurify = DOMPurify;

marked.setOptions({ breaks: true });

Alpine.start();
