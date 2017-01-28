/**
 * Detects device type (desktop/mobile)
 *
 * @returns {string}
 */
function deviceType() {
    return window.getComputedStyle(document.querySelector('body'), '::after').getPropertyValue('content').replace(/"/g, "").replace(/'/g, "");
}

$(document).ready(function($){

    // READY

});
