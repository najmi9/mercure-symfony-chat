import React, { useCallback } from 'react';
import '../../../styles/conv.css';
import Msgs from '../pages/Msgs';
import ReactDOM from 'react-dom';

const Conv = ({ conv }) => {

    const handleConvClick = (id) => {
        //alert(id)
        const msgsContainer = document.querySelector('div.msgs');
        ReactDOM.render(<Msgs conv={id}/>, msgsContainer);
    }

    return (
        <div onClick={() => handleConvClick(conv.id)} className="conv card rounder shadow-lg p-2 m-2">
            <div className="card-header">
            <img src={conv.user.avatar? conv.user.avatar: `https://randomuser.me/api/portraits/thumb/women/${conv.user.id}.jpg`} 
            alt={conv.user.name} className="rounded-circle"/> 
            <span className="font-weight-bolder text-success h6"> {conv.user.name || conv.user.email} </span>
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