import React, { useEffect, useState } from 'react';
import User from '../components/user';

const Users = () => {

    const [users, setUsers] = useState([]);

    useEffect(() => {
        fetch('/users')
        .then(r => r.json())
        .then(res => {
            setUsers(JSON.parse(res))
        })
    }, []);

    return(
        <div className="users">
            { users.map(u => (<User key={u.id} user={u} />)) }
        </div>
    );
};

export default Users;