import React, { useEffect } from 'react';
import Msg from '../components/msg';
import MsgForm from '../components/msg_form';
import useEventSource from '../hooks/useEventSource';
import useFetch from '../hooks/useFetch';
import { hub_url, msgs_url, msgTopic } from '../urls';
import Loader from '../utils/loader';

const Msgs = ({conv}) => {
    const userId = parseInt(document.querySelector('div.data').dataset.user);
    const [loading, load, msgs,  setMsgs] = useFetch(msgs_url(conv));
    const [eventSource] = useEventSource(setMsgs, msgTopic(conv));

    useEffect( async () => {
        load();    
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