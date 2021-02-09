import React from 'react';
import { render } from 'react-dom';
import TooglerIconApp from './HermesReactTooglerIcon';
import TestimonyApp from './HermesReactTestimony';
import BlockApp from './HermesReactBlock';

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

// block
const blocks = document.getElementById('react-data-block');
if(blocks){
    const nbBlock = document.getElementById('react-data-block').dataset.nbblock;
    for (var i = 1; i <= nbBlock; i++){
        const idBlock = 'react-block' + i;
        const idDataBlock = 'react-data-block' + i;
        const datasetBlock = 'block' + i;

        const block = document.getElementById(idBlock);
        const imgClass = block.dataset.imgclass;
        const dataBlock = document.getElementById(idBlock);
        const dataBlocks =  JSON.parse(document.getElementById(idDataBlock).dataset[datasetBlock]);
        if(block) {
            if(dataBlock) {
                render( <BlockApp datas={dataBlocks} imgClass={imgClass}
                />, block);
            }
        }
    }
}
