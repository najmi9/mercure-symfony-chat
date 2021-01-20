import React from 'react';

const Msg = ({ msg }) => (
    <li key={m.id} className={msg.isMyMsg ? 'my_msg' : 'not_my_msg'}>
        <div>
            <img src={msg.user.avatar ? msg.user.avatar : `https://randomuser.me/api/portraits/thumb/women/${msg.user.id}.jpg`}
                alt={msg.user.name} className="rounded-circle" />
            <span> {msg.content} </span>
        </div>
        <small className="text-muted"> {new Date(msg.updated).toLocaleString()} </small>
    </li>
);

export default Msg;