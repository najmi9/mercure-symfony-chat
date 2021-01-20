import React, { useState } from 'react';
import Msgs from '../pages/msgs';
import ReactDOM from 'react-dom';

const User = ({ user }) => {
    const [convId, setConvId] = useState(0);

    const handleUserClick = id => {
    fetch(`/conversation/new/${id}`, {
        method: 'POST'
    })
    .then(r => r.json())
    .then(res => {
        setConvId(res.id);
        const msgsContainer = document.querySelector('div.msgs');
        ReactDOM.render(<Msgs conv={res.id}/>, msgsContainer);
    });
    }

    return (
        <div className="user card rounder shadow-lg p-2 m-3" onClick={()=>handleUserClick(user.id)}>
            <div className="card-header">
                <img src={user.avatar ? user.avatar : `https://randomuser.me/api/portraits/thumb/women/${user.id}.jpg`}
                    alt={user.name} className="rounded-circle" />
                <span className="font-weight-bolder text-success h6"> {user.name || user.email} </span>
            </div>
        </div>
    );
}

export default User;