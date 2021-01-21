import React, { Component } from 'react';

export default class DeliveryFreeMessage extends Component {
    render() {
        let message = '';
        // const listItems = this.props.itemOptions.map((number) =>  <li>{number}</li>)
        if (this.props.deliveryFree == 'free') {
            message =  <div><h3>Livraison offerte!</h3><span>❤️</span></div>;
        }
        return message;
    }
}
