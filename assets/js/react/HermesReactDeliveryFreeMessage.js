import React, { Component } from 'react';

export default class DeliveryFreeMessage extends Component {
    render() {
        let message = '';
        if (0 !=this.props.deliveryFreeLimit) {
            message =  <div><h3>{this.props.deliveryFreeFrom}</h3></div>;
            if (1 ==this.props.deliveryFree) {
                message =  <div><h3><span>❤️</span> {this.props.deliveryFreeMessage} <span>❤️</span></h3></div>;
            }
        }
        return message;
    }
}
