import React, { useEffect, useState } from 'react';
import Msg from '../components/msg';

const Msgs = ({conv}) => {
    const [msgs, setMsgs] = useState([]);

    useEffect(() => {
        fetch(`/converstation/${conv}/msgs`)
        .then(res => res.json())
        .then(res => setMsgs(res))
    }, []);

    return (
        <div className="msg">
            <ul>
                { msgs.map(m => (<Msg msg={m} />)) }
            </ul>
        </div>
    );
};

export default Msgs;