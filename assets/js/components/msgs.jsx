import React, { useCallback, useEffect } from 'react';
import Msg from '../components/msg';
import MsgForm from '../components/msg_form';
import useFetch from '../hooks/useFetch';
import { hub_url, msgs_url, msgTopic } from '../urls';
import Loader from '../utils/loader';

const Msgs = ({conv}) => {
    const userId = parseInt(document.querySelector('div.data').dataset.user);
    const [loading, load, msgs,  setMsgs] = useFetch(msgs_url(conv));

    const listenToMercure = useCallback(() => {
        const url = new URL(hub_url);
        url.searchParams.append('topic', msgTopic(conv));
        const eventSource = new EventSource(url, { withCredentials: true });
    
        /**
         * its better to use callback
         * 
         * @param {MessageEvent} e 
         */
        eventSource.onmessage = e => {
            const data = JSON.parse(e.data);
            setMsgs(msgs => [...msgs, data]);
            document.querySelector('div.msgs').scrollTop = document.querySelector('div.msgs').scrollHeight; 
        }
        return eventSource;
    }, [conv]);

    useEffect( async () => {
        load(); 
        const eventSource = listenToMercure();   
        return function cleanup() {
            eventSource.close();
        }
    }, [conv]);

    return (
        <>      
            { loading && (<Loader />) }
            { !loading && (msgs.map(m => (<Msg msg={m} key={m.id} userId={userId}/>))) }
            { !loading && (<MsgForm id={conv} />) }
        </>
    );
};

export default Msgs;