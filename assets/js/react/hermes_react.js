import React from 'react';
import { render } from 'react-dom';
import TooglerIconApp from './HermesReactTooglerIcon';

// nav button responsive
const togglerIcon = document.getElementById('react-toggler-icon')
if(togglerIcon) {
    render( <TooglerIconApp />, togglerIcon);
}
