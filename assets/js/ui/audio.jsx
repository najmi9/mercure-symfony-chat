import React from 'react';

const Audio = ({src, ...props}) => {

    return (<audio src={src} controls={true} className="audio-msg" {...props} />);
}

export default Audio;
