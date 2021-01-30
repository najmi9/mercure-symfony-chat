import React, { useEffect } from 'react';
import User from './user';
import { users_url } from '../urls';
import useFetchAll from '../hooks/useFetchAll';
import Loader from '../utils/loader';

const Users = () => {
    const {loading, load, data: users} = useFetchAll(users_url);

    useEffect( async() => {
        await load();
    }, []);

    return(
        <>
            { loading && (<Loader />) }
            { !loading && (users.map(u => (<User key={u.id} user={u} />))) }
        </>
    );
};

export default Users;