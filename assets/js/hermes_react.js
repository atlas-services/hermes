import React from 'react';
import ReactDom from 'react-dom';

// const el = React.createElement('h2', null, 'Lift History!');
const el = React.createElement(
    'h1',
    null,
    'Hermes react!!! ',
    React.createElement('span', null, '❤️')
);
ReactDom.render(el, document.getElementById('hermes-app'));