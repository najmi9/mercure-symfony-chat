import React, { useEffect, useState } from 'react';
import Msg from '../components/msg';
import MsgForm from '../components/msg_form';

const Msgs = ({conv}) => {
    const [msgs, setMsgs] = useState([]);

    useEffect(() => {
        fetch(`/conversation/${conv}/msgs`)
        .then(res => res.json())
        .then(res => setMsgs(res));

        const url = new URL('http://localhost:3000/.well-known/mercure');
        url.searchParams.append('topic', `http://mywebsite.com/msg/${conv}`);
        const eventSource = new EventSource(url, { withCredentials: true });

        eventSource.onmessage = e => {
            const data = JSON.parse(e.data);
            setMsgs([...msgs, data]);  
        };

    }, [conv]);

    return (
        <div className="msgs">      
            { msgs.map(m => (<Msg msg={m} key={m.id} />)) }
            <MsgForm id={conv} />
        </div>
    );
};

export default Msgs;