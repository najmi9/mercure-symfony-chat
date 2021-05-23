import React from 'react';
import BigImage from './bigImage';

const Image = ({id, src}) => {

    return (
        <>
            <img src={src} width={150} data-toggle="modal" data-target={"#showImageModal-"+id} height={150} />
            <BigImage src={src} id={id}/>
        </>
    );
}

export default Image;
