import React from 'react';

const Loader = ({ color='blue', width=200, strokeWidth=20, minHeight=100}) => {

    return (
        <section className="d-flex justify-content-center align-items-center"
            style={{ 'minHeight': minHeight + 'vh' }}>
            <spinning-dots
                style={{ "width": width + "px", "strokeWidth": strokeWidth + "px", "color": color }}
                dots="8">
            </spinning-dots>
        </section>
    );
}

export default Loader;

