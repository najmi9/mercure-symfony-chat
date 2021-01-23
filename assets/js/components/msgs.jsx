import React, { useEffect, useState } from 'react';
import Msg from '../components/msg';
import MsgForm from '../components/msg_form';
import { hub_url, msgs_url, msgTopic } from '../urls';

const Msgs = ({conv}) => {
    const [msgs, setMsgs] = useState([]);
    const userId = parseInt(document.querySelector('div.data').dataset.user);
 
    useEffect(() => {
        fetch(msgs_url(conv))
        .then(res => res.json())
        .then(res => setMsgs(res));

        const url = new URL(hub_url);

        url.searchParams.append('topic', msgTopic(conv));
        // Resolve the problem of topics
        /*
        [6, 7, 8, 11, 9, 10].forEach(e => url.searchParams.append('topic', msgTopic(e)))
        */
        const eventSource = new EventSource(url, { withCredentials: true });
        
        /**
         * @param {MessageEvent} e 
         */
        eventSource.onmessage = e => {
            console.log('msgs', e);
            const data = JSON.parse(e.data);
            document.querySelector('div.msgs').scrollTop = document.querySelector('div.msgs').scrollHeight; 
            setMsgs(msgs => [...msgs, data]);
        };

        return function cleanup() {
            eventSource.close();
        }
    }, [conv]);

    return (
        <>      
            { msgs.map(m => (<Msg msg={m} key={m.id} userId={userId}/>)) }
            <MsgForm id={conv} />
        </>
    );
};

export default Msgs;