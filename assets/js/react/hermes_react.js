import React from 'react';
import { render } from 'react-dom';
import TooglerIconApp from './HermesReactTooglerIcon';
import DeliveryFreeMessage from './HermesReactDeliveryFreeMessage';

// nav button responsive
const togglerIcon = document.getElementById('react-toggler-icon')
if(togglerIcon) {
    render( <TooglerIconApp />, togglerIcon);
}

//delivery free message
const deliveryFree = document.getElementById('react-delivery-free');
if(deliveryFree){
    render(<DeliveryFreeMessage
            {...(deliveryFree.dataset)}
        />,
        deliveryFree
    );
}
