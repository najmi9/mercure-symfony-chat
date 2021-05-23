import React, { useCallback, useEffect, useRef, useState } from 'react';
import Msg from '../components/msg';
import MsgForm from '../components/msg_form';
import useFetchAll from '../hooks/useFetchAll';
import { hub_url, msgs_url, msgTopic } from '../urls';
import Loader from '../utils/loader';
import notify from '../utils/notify';
import ConvHeader from '../components/convHeader';
import { userId } from '../utils/userData';

/**
 * 
 * @todo Not Unique Key + Improvements
 * 
 */
const Conv = ({match}) => {
    const conv = match.params.id;

    const {loading, load, data: msgs, setData: setMsgs, count} = useFetchAll();

    const [page, setPage] = useState(1);
    const max = 4;

    const ref = useRef(null);

    const listenToMercure = useCallback(() => {
        const url = new URL(hub_url);
        url.searchParams.append('topic', msgTopic(conv));
        const eventSource = new EventSource(url, { withCredentials: true });

        eventSource.onmessage = e => {
            const data = JSON.parse(e.data);
            if (userId !== data.user.id) {
                notify();
            }

            setMsgs(msgs => [...msgs, data]);
            if (ref.current) {
                ref.current.scrollTop = ref.current.scrollHeight; 
            }
        }
        return eventSource;
    }, [conv, page]);


    const handlePage = useCallback(() => {
        setPage(page + 1)
    }, [conv, page])

    useEffect(() => {
        load(`${msgs_url(conv)}?page=${page}&max=${max}`, true);

        const eventSource = listenToMercure(); 
        if (ref.current) {
            ref.current.scrollTop = ref.current.scrollHeight; 
        } 

        return function cleanup() {
            eventSource.close();
        }
    }, [page]);

    return(
        <div className="container mt-5">  
            {loading && <Loader width= {50} strokeWidth={10} minHeight={10}/>}
            <ConvHeader conv={conv} />

            <div className="msgs" ref={ref}>
                {!loading && count > page * max && <div className="box text-center">
                    <button className="btn btn-primary" onClick={handlePage}>
                        <i className={"fas fa-sync-alt"}></i> load more
                    </button>
                </div>}
        
                { !loading && (msgs.map(m => (<Msg msg={m} key={m.id} userId={userId} setMsgs={setMsgs} conv={conv}/>))) }

                { !loading && (<MsgForm id={conv} />) }
            </div>
        </div>
    );
}


export default Conv;