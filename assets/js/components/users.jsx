import React, { useEffect, useState } from 'react';
import User from './user';
import { users_url } from '../urls';

const Users = () => {

    const [users, setUsers] = useState([]);

    useEffect(() => {
        fetch(users_url)
        .then(r => r.json())
        .then(res => setUsers(res));
    }, []);

    return(
        <>
            { users.map(u => (<User key={u.id} user={u} />)) }
        </>
    );
};

export default Users;