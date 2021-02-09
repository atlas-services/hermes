import React, { Component } from 'react';

export default class BlockApp extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        let datas = this.props.datas;
        let imgClass = this.props.imgClass;
        const elements = datas.map((data) => {
            return (
                <div
            className="col-lg-4 col-md-7 col-sm-8"
            key={data.id}
                >
                <div className="single-services text-center mt-30 wow fadeIn" data-wow-delay="0.2s" data-wow-duration="1s">
                <div className="services-icon"><img height="180px" alt={data.name} className={imgClass} src={data.src} /></div>
                <div className="services-content mt-30">
                <h4 className="services-title"><a href="#">{data.name}</a></h4>
                {data.comment}
                </div>
                </div>
                </div>
            )
        });
        return <React.Fragment>
            {elements}
        </React.Fragment>
        ;
    }
}
