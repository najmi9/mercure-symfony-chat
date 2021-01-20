import App from './js/src/index';
import React from 'react';
import ReactDOM from 'react-dom';
import './styles/msg.css';

const div = document.querySelector('div#root');

ReactDOM.render(
    <React.StrictMode>
        <App />
    </React.StrictMode>, 
    div
);