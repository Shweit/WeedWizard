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
import './script/CannaDoseCalculator';
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

window.bootstrap = bootstrap;
