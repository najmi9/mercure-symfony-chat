import React, { useEffect, useState } from 'react';
import { new_conv_url } from '../urls';
import useFetch from '../hooks/useFetch';
import { Link } from 'react-router-dom/cjs/react-router-dom.min';

const User = React.memo(({ user }) => {
    const {loading, load} = useFetch(new_conv_url(user.id), 'POST');
    const [id, setId] = useState(0);

    useEffect(() => {
        load().then(res => setId(res.id));
    }, [user])

    return (
        <div className="user card rounder shadow-lg p-2 m-3">
            <Link to={"/convs/"+id} className="link-to-conv">
                <div className="conv-header">
                    <img src={user.avatar} alt={user.name} className="rounded-circle" width="40" height="40" />
                    <span className="font-weight-bolder text-success h6"> {user.name} </span>
                </div>
            </Link>
        </div>
    );
});

export default User;