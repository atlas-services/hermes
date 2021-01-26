import React, { Component } from 'react';

export default class DeliveryFreeMessage extends Component {
    render() {
        let message = '';
        if (0 !=this.props.deliveryFreeLimit) {
            message =  <div><h5>{this.props.deliveryFreeFrom}</h5><hr></hr></div>;
            if (1 ==this.props.deliveryFree) {
                message =  <div><h5><span>❤️</span> {this.props.deliveryFreeMessage} <span>❤️</span></h5><hr></hr></div>;
            }
        }
        return message;
    }
}
