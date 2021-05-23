import React, { useEffect, useState } from 'react';
import User from './user';
import { users_url } from '../urls';
import Loader from '../utils/loader';
import useFetchAll from '../hooks/useFetchAll';

const Users = () => {
    const {loading, load, data: users, count} = useFetchAll();
    const [page, setPage] = useState(1);
    const max = 4;

    useEffect( async() => {
        await load(`${users_url}?page=${page}&max=${max}`);
    }, [page]);

    return(
        <>
            { loading && (<Loader />) }
            { !loading 
                && <div className="users"> 
                    { users.map(u => <User key={u.id} user={u} />) } 
                    {count > page * max && <div className="box text-center">
                        <button className="btn btn-primary" onClick={() => setPage(page + 1)}>
                            <i className={"fas fa-sync-alt"}></i> load more
                        </button>
                    </div>}
            </div>}
        </>
    );
};

export default Users;