import React, { useEffect } from 'react';
import User from './user';
import { users_url } from '../urls';
import useFetch from '../hooks/useFetch';
import Loader from '../utils/loader';

const Users = () => {
    const [loading, load, users] = useFetch(users_url);

    useEffect(() => {
        load();
    }, []);

    return(
        <>
            { loading && (<Loader />) }
            { !loading && (users.map(u => (<User key={u.id} user={u} />))) }
        </>
    );
};

export default Users;