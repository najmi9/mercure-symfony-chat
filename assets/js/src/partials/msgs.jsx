import React, { useEffect, useState } from 'react';
import Msg from '../components/msg';
import MsgForm from '../components/msg_form';
import { hub_url, msgs_url, msgTopic } from '../urls';

const Msgs = ({conv}) => {
    const [msgs, setMsgs] = useState([]);

    useEffect(() => {
        fetch(msgs_url(conv))
        .then(res => res.json())
        .then(res => setMsgs(res));

        const url = new URL(hub_url);
        url.searchParams.append('topic', msgTopic(conv));
        const eventSource = new EventSource(url, { withCredentials: true });
        const userId = parseInt(document.querySelector('div.data').dataset.user);

        /**
         * @param {MessageEvent} e 
         */
        eventSource.onmessage = e => {
            console.log(e)
            const data = JSON.parse(e.data);
            document.querySelector('div.msgs').scrollTop = document.querySelector('div.msgs').scrollHeight; 
            setMsgs(msgs => [...msgs, Object.assign({}, data, {isMyMsg: userId === data.user.id})]);
        };
    }, [conv]);

    return (
        <>      
            { msgs.map(m => (<Msg msg={m} key={m.id}/>)) }
            <MsgForm id={conv} />
        </>
    );
};

export default Msgs;