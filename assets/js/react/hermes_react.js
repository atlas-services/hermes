import React from 'react';
import { render } from 'react-dom';
import TooglerIconApp from './HermesReactTooglerIcon';
import DeliveryFreeMessage from './HermesReactDeliveryFreeMessage';
import TestimonyApp from './HermesReactTestimony';

// nav button responsive
const togglerIcon = document.getElementById('react-toggler-icon')
if(togglerIcon) {
    render( <TooglerIconApp />, togglerIcon);
}
// testtimonials
const testimony = document.getElementById('react-testimony');
const dataTestimony = document.getElementById('react-data-testimony');
const dataTestimonys =  JSON.parse(document.getElementById('react-data-testimony').dataset.testimonys);
if(testimony) {
    if(dataTestimony) {
        render( <TestimonyApp comments={dataTestimonys}
  />, testimony);
    }
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
