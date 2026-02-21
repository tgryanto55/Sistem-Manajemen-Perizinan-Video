// Custom Alpine.js
import Alpine from 'alpinejs'
import ajax from '@imacrayon/alpine-ajax'

window.Alpine = Alpine

Alpine.plugin(ajax)
Alpine.start()

document.addEventListener('alpine:init', () => {
    // defined data
});
