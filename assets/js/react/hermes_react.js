import React from 'react';
import { render } from 'react-dom';
import TooglerIconApp from './HermesReactTooglerIcon';
import DeliveryFreeMessage from './HermesReactDeliveryFreeMessage';

// nav button responsive
if(document.getElementById('toggler-icon-app')) {
    render( < TooglerIconApp / >, document.getElementById('toggler-icon-app'));
}

//delivry free message
const deliveryFree = document.getElementById('delivery-free');
if(deliveryFree){
    render(<DeliveryFreeMessage
            {...(deliveryFree.dataset)}
        />,
        deliveryFree
    );
}
