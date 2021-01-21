import React, {  } from 'react';
import '../../../styles/conv.css';
import Msgs from '../partials/msgs';
import ReactDOM from 'react-dom';

const Conv = ({ conv }) => {

    const handleConvClick = (id) => {
        const msgsContainer = document.querySelector('div.msgs');
        ReactDOM.render(<Msgs conv={id}/>, msgsContainer);
    }

    return (
        <div onClick={() => handleConvClick(conv.id)} className="conv card rounder shadow-lg p-2 m-2">
            <div className="card-header">
            <img src={conv.user.avatar} 
            alt={conv.user.name} className="rounded-circle"/> 
            <span className="font-weight-bolder text-success h6"> {conv.user.name} </span>
            </div>
            <div className="card-body">
                <p className="text-primary">{ conv.msg}</p>
                <small className="text-muted">
                    {new Date(conv.date).toLocaleString()}
                </small>
            </div>
        </div>
    );
}

export default Conv;