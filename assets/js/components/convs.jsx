import React, { useCallback, useEffect } from 'react';
import Conv from './conv';
import { convs_url, convTopic, hub_url } from '../urls';
import useFetch from '../hooks/useFetch';
import Loader from '../utils/loader';

const Convs = () => {
    const userId = parseInt(document.querySelector('div.data').dataset.user);
    const [loading, load, convs, setConvs] = useFetch(convs_url);

    const listenToMercure = useCallback(() => {
        const url = new URL(hub_url);
        url.searchParams.append('topic', convTopic(userId));
        const eventSource = new EventSource(url, { withCredentials: true });
    
        /**
         * its better to use callback
         * 
         * @param {MessageEvent} e 
         */
        eventSource.onmessage = e => {
            console.log('msg');
            const userId = parseInt(document.querySelector('div.data').dataset.user);
            const conv = JSON.parse(e.data);
            conv['user'] = conv.users.filter(u => u.id !== userId)[0]; 
            delete conv['users'];
            if (conv.new) {
                setConvs(convs => [conv, ...convs]);
            } else {
                setConvs(convs => {
                    const oldConvs = [...convs];
                    const oldData = {...oldConvs.filter(e => e.id === conv.id)[0]};
                    oldData.msg = conv.msg; 
                    oldData.date = conv.date;
                    const cs = oldConvs.filter(c => c.id !== conv.id);
                    cs.splice(0, 0, oldData);
                    return cs;
                });
            }
        }

        return eventSource;
    }, []);

    useEffect(() => {
        load();
        const eventSource = listenToMercure();   
        return function cleanup() {
            eventSource.close();
        }
    }, []);

    return (
        <>
            { loading && (<Loader />)}
            { !loading &&  (convs.map(c => (<Conv key={c.id} conv={c} />)))}
        </>
    );
}

export default Convs;