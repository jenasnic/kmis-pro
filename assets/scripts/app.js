// >>> For help tool only
import './help-tool';
// <<< For help tool only

import '../styles/app.scss';

import './common/cookie';
import './common/form';
import './common/captcha';
import './common/components/flash';
import './common/components/floating-shortcut';

import AOS from 'aos';
AOS.init({
    once: true,
});

import './front/home';
import './front/menu';
