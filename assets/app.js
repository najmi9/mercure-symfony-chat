import App from './js/src/index';
import React from 'react';
import ReactDOM from 'react-dom';


const div = document.querySelector('div#root');

ReactDOM.render(
    <React.StrictMode>
        <App />
    </React.StrictMode>, 
    div
);