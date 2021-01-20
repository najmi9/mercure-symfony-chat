import React from 'react';

const User = ({user}) => (
    <div className="user card rounder shadow-lg p-2 m-3">
        <div className="card-header">
           <img src={user.avatar? user.avatar: `https://randomuser.me/api/portraits/thumb/women/${user.id}.jpg`} 
           alt={user.name} className="rounded-circle"/> 
           <span className="font-weight-bolder text-success h6"> {user.name || user.email} </span>
        </div>
    </div>
);

export default User;