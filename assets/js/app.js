/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import '../css/global.scss';
import '../css/app.css';

require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

const $ = require('jquery');
import 'bootstrap';

import React from 'react';
import ReactDOM from 'react-dom';
import App from './components/app';

ReactDOM.render(<App/>, document.getElementById('app'));
