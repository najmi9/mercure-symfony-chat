import App from './js/index';
import React from 'react';
import ReactDOM from 'react-dom';
import './styles/msg.css';
import './styles/conv.css';
import reportWebVitals from './js/reportWebVitals';

const div = document.querySelector('div#root');

ReactDOM.render(
    <React.StrictMode>
        <App />
    </React.StrictMode>, 
    div
);

reportWebVitals();