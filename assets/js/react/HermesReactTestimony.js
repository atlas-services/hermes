import React, { Component } from 'react';

export default class TestimonyApp extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        let comments = this.props.comments;
        const commentElements = comments.map((testimony) => {
            return (
                <div
                className="testimonial-item"
                key={testimony.id}
                >
                <h3>{testimony.name}</h3>
                <p>{testimony.comment}</p>
                </div>
            )
        });
        return <div className="col-lg-8"><div className="owl-carousel testimonials-carousel">
            {commentElements}
        </div>
        </div>
        ;
    }
}
