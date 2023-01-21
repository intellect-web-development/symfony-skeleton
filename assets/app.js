/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import jquery from 'jquery';
import Routing from '../public/bundles/fosjsrouting/js/router.min';

import '../node_modules/semantic-ui-css/semantic.min.css'
import '../vendor/sylius/ui-bundle/Resources/private/sass/main.scss'
import '../node_modules/select2/dist/css/select2.min.css'
import './styles/app.css';

import 'semantic-ui-css'
import '../node_modules/semantic-ui-calendar/dist/calendar.min'
import '../vendor/sylius/ui-bundle/Resources/private/js/app.js'
import '../vendor/sylius/ui-bundle/Resources/private/js/sylius-auto-complete.js'
import '../node_modules/inputmask/dist/jquery.inputmask.js'
import '../node_modules/select2/dist/js/select2.js'

global.$ = global.jQuery = jquery;

const routes = require('../public/js/fos_js_routes.json');
Routing.setRoutingData(routes);