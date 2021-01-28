import React, { useCallback, useEffect, useRef } from 'react';
import Msg from '../components/msg';
import MsgForm from '../components/msg_form';
import useFetch from '../hooks/useFetch';
import { hub_url, msgs_url, msgTopic } from '../urls';
import Loader from '../utils/loader';

const Conv = ({match, history}) => {
    const conv = match.params.id;
    if (!conv) {
        history.push('/');
    }

    const userId = parseInt(document.querySelector('div.data').dataset.user);
    const [loading, load, msgs, setMsgs] = useFetch(msgs_url(conv));
    const ref = useRef(null);

    const listenToMercure = useCallback(() => {
        const url = new URL(hub_url);
        url.searchParams.append('topic', msgTopic(conv));
        const eventSource = new EventSource(url, { withCredentials: true });
    
        /**
         * @param {MessageEvent} e 
         */
        eventSource.onmessage = e => {
            const data = JSON.parse(e.data);
            setMsgs(msgs => [...msgs, data]);
            ref.current.scrollTop = ref.current.scrollHeight; 
        }
        return eventSource;
    }, [conv]);

    useEffect( async () => {
        await load(); 
        const eventSource = listenToMercure(); 
        ref.current.scrollTop = ref.current.scrollHeight; 
        return function cleanup() {
            eventSource.close();
        }
    }, [conv]);


    return(
        <div className="container mt-5">
            <div className="msgs" ref={ref}>
                { loading && (<Loader />) }
                { !loading && (msgs.map(m => (<Msg msg={m} key={m.id} userId={userId}/>))) }
                { !loading && (<MsgForm id={conv} />) }
            </div>
        </div>
    );
}

export default Conv;