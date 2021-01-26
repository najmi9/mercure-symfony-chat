import React, { useCallback } from 'react';
import Msgs from './msgs';
import ReactDOM from 'react-dom';
import { new_conv_url } from '../urls';
import useFetch from '../hooks/useFetch';

const User = React.memo(({ user }) => {
    const [loading, load] = useFetch(new_conv_url(user.id), 'POST');

    const handleUserClick = useCallback( async() => {
        // The user should not be clickable where loading is true
        const res = await load();
        const msgsContainer = document.querySelector('div.msgs');
        ReactDOM.render(<Msgs conv={res.id} />, msgsContainer);
        msgsContainer.scrollTop = msgsContainer.scrollHeight;   
    }, [user]);

    return (
        <div className="user card rounder shadow-lg p-2 m-3" onClick={handleUserClick} disabled={loading}>
            <div className="conv-header">
                <img src={user.avatar} alt={user.name} className="rounded-circle" width="40" height="40" />
                <span className="font-weight-bolder text-success h6"> {user.name} </span>
            </div>
        </div>
    );
});

export default User;