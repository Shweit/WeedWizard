/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './bootstrap';

import * as bootstrap from 'bootstrap';
import './styles/app.scss';

import {sanitizeHtml} from "bootstrap/js/src/util/sanitizer";

require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

window.bootstrap = bootstrap;
window.mapbox_access_token = 'pk.eyJ1Ijoic2h3ZWl0IiwiYSI6ImNsdncwZmI5cjIxY20ycXF6N3FpM2xoNDIifQ.NLaLrz1CAO5jEZtrqwhH4g';
window.mapbox_session_token = 'weedwizard_webapplication';

// This will trigger all tooltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

// This will load the toast on page load
window.addEventListener('load', function () {
    const toastLiveExample = document.getElementsByClassName('toast');
    [...toastLiveExample].forEach(toast => {
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toast)
        toastBootstrap.show()
    });
});

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

window.getUserLocation = function () {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject(new Error("Geolocation is not supported by this browser."));
        } else {
            navigator.geolocation.getCurrentPosition(resolve, reject, {
                timeout: 10000,
                maximumAge: 60000
            });
        }
    });
}

// This will load the toast on page load
window.addEventListener('load', function () {
    const toastLiveExample = document.getElementsByClassName('toast');
    [...toastLiveExample].forEach(toast => {
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toast)
        toastBootstrap.show()
    });
});

window.loadCSS = function (urls) {
    if (typeof urls === 'string') {
        urls = [urls];
    }

    urls.forEach(url => {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = url;
        document.head.appendChild(link);
    });
}

window.loadJS = function (urls) {
    if (typeof urls === 'string') {
        urls = [urls];
    }

    urls.forEach(url => {
        const script = document.createElement('script');
        script.src = url;
        document.head.appendChild(script);
    });
}

window.showToast = function (message, type = 'success') {
    const toastContainer = document.getElementById('toast-container');

    const toast = document.createElement('div');
    toast.classList.add('toast', type === 'success' ? 'border-success' : 'border-danger');
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.style.opacity = '1';
    toast.style.marginBottom = '10px';

    const toastHeader = document.createElement('div');
    toastHeader.classList.add('toast-header', type === 'success' ? 'text-success' : 'text-danger');

    const title = document.createElement('strong');
    title.textContent = 'WeedWizard';

    toastHeader.appendChild(title);

    const toastBody = document.createElement('div');
    toastBody.classList.add('toast-body');
    toastBody.innerHTML = sanitizeHtml(message);

    toast.appendChild(toastHeader);
    toast.appendChild(toastBody);

    toastContainer.appendChild(toast);

    const toastBootstrap = new bootstrap.Toast(toast);
    toastBootstrap.show();
}

window.toggleLikeBlogEntry = function (blogEntryId, action, link) {
    if (action === 'like') {
        fetch('/blog/like/' + blogEntryId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
        })
            .then(response => {
                return response.json()
            })
            .then(data => {
                if (data.error) {
                    window.showToast(data.error, 'error');
                } else {
                    link.setAttribute('onclick', `window.toggleLikeBlogEntry(${blogEntryId}, 'unlike', this)`);
                    link.innerHTML = '<i class="fa-solid fa-heart me-2"></i> ' + sanitizeHtml(data.likes);
                }
            })
    } else {
        fetch('/blog/unlike/' + blogEntryId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
        })
            .then(response => {
                return response.json()
            })
            .then(data => {
                if (data.error) {
                    window.showToast(data.error, 'error');
                } else {
                    link.setAttribute('onclick', `window.toggleLikeBlogEntry(${blogEntryId}, 'like', this)`);
                    link.innerHTML = '<i class="fa-regular fa-heart me-2"></i> ' + sanitizeHtml(data.likes);
                }
            })
    }
}

window.copyToClipboard = function (content, toastMessage) {
    navigator.clipboard.writeText(content)
        .then(() => {
            window.showToast(toastMessage, 'success')
        });
}

window.addEventListener('DOMContentLoaded', (event) => {
    if (!localStorage.getItem('ageVerfied')) {
        const ageModal = new window.bootstrap.Modal(document.getElementById('ageVerification'));
        ageModal.show();
    }

    document.getElementById('ageVerification_youngerThan18').addEventListener('click', () => {
        window.location.href = 'https://www.google.com';
    });
    document.getElementById('ageVerification_olderThan18').addEventListener('click', verifyAge);

    function verifyAge() {
        localStorage.setItem('ageVerfied', 'true');
        const ageModal = window.bootstrap.Modal.getInstance(document.getElementById('ageVerification'));
        ageModal.hide();
    }

    // onscroll add fixed postion to the navbar
    const navbar = document.getElementById('navbar');
    const navbarHeight = navbar.offsetHeight;

    const navBarPlaceholder = document.createElement('div');
    navBarPlaceholder.style.height = navbarHeight + 'px';

    window.addEventListener('scroll', () => {
        if (scrollY > 0) {
            navbar.insertAdjacentElement('beforebegin', navBarPlaceholder);
            navbar.style.position = 'fixed';
            navbar.style.top = '0';
            navbar.style.width = '100%';
            navbar.style.zIndex = '9999';
        } else {
            navBarPlaceholder.remove();
            navbar.style.position = 'relative';
            navbar.style.top = '0';
            navbar.style.width = '100%';
            navbar.style.zIndex = '9999';
        }
    });

    if (scrollY > 0) {
        navbar.insertAdjacentElement('beforebegin', navBarPlaceholder);
        navbar.style.position = 'fixed';
        navbar.style.top = '0';
        navbar.style.width = '100%';
        navbar.style.zIndex = '9999';
    }

    const navbarMenu = document.getElementById('navbarNav');
    const dropdown = document.getElementById('more-features-dropdown');
    const navbarToggler = document.getElementById('navbar-toggler');

    document.getElementById('more-features').addEventListener('click', () => {
        const navbarTogglerDisplay = window.getComputedStyle(navbarToggler).display;
        if (dropdown.classList.contains('show') && navbarTogglerDisplay !== 'none') {
            dropdown.style.transform = `translateY(-${navbarMenu.offsetHeight}px)`;
            navbarMenu.classList.add('no-transition');
        }
    });

    navbarToggler.addEventListener('click', () => {
        if (dropdown.classList.contains('show')) {
            navbarMenu.classList.add('show');
            dropdown.classList.remove('show');
            dropdown.style.transform = `translateY(0px)`;

            setTimeout(() => {
                navbarMenu.classList.remove('no-transition');
            }, 350);
        }
    });

    window.addEventListener('resize', () => {
        dropdown.classList.remove('show');
        dropdown.style.transform = `translateY(0px)`;
    });
});