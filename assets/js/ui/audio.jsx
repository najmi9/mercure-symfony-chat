import React from 'react';

const Audio = ({src}) => {

    return (<audio src={src} controls={true} className="audio-msg" />);
}

export default Audio;
