import React, { useEffect, useState } from 'react';
import User from '../components/user';
import { users_url } from '../urls';

const Users = () => {

    const [users, setUsers] = useState([]);

    useEffect(() => {
        fetch(users_url)
        .then(r => r.json())
        .then(res => {
            setUsers(res)
        })
    }, []);

    return(
        <div className="users">
            { users.map(u => (<User key={u.id} user={u} />)) }
        </div>
    );
};

export default Users;