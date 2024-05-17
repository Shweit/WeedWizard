/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import * as bootstrap from 'bootstrap';
import './bootstrap';
import './styles/app.scss';
import './script/chartJsConfig';
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

window.bootstrap = bootstrap;
window.mapbox_access_token = 'pk.eyJ1Ijoic2h3ZWl0IiwiYSI6ImNsdncwZmI5cjIxY20ycXF6N3FpM2xoNDIifQ.NLaLrz1CAO5jEZtrqwhH4g';
window.mapbox_session_token = 'weedwizard_webapplication';

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

// This function is used to debounce the input event listener
// It is used to prevent the input event listener from firing too often
window.debounce = function (func, delay) {
    let debounceTimer;
    return function () {
        const context = this;
        const args = arguments;
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => func.apply(context, args), delay);
    };
}

window.calculateDistance = function (lat1, long1, lat2, long2) {
    const R = 6371; // Radius der Erde in Kilometern
    const deltaLat = toRadians(lat2 - lat1);
    const deltaLong = toRadians(long2 - long1);
    lat1 = toRadians(lat1);
    lat2 = toRadians(lat2);

    const a = Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
        Math.cos(lat1) * Math.cos(lat2) *
        Math.sin(deltaLong / 2) * Math.sin(deltaLong / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    return R * c;
}

function toRadians(degree) {
    return degree * (Math.PI / 180);
}

// This will load the toast on page load
window.addEventListener('load', function () {
    const toastLiveExample = document.getElementsByClassName('toast');
    [...toastLiveExample].forEach(toast => {
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toast)
        toastBootstrap.show()
    });
});