import React, { useEffect, useState } from 'react';
import Conv from './conv';
import { convs_url, convTopic, hub_url } from '../urls';

const Convs = () => {
    const [convs, setConvs] = useState([]);

    const fetchConvs = async () => {
        const r = await fetch(convs_url);
        const res = await r.json();
        setConvs(res);
    }

    const listenToConvs = () => {
        const url = new URL(hub_url);
        const userId = parseInt(document.querySelector('div.data').dataset.user);
        url.searchParams.append('topic', convTopic(userId));
        const eventSource = new EventSource(url, { withCredentials: true });
        eventSource.onmessage = e => {
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
                    cs.splice(0, 0, oldData)
                    return cs
                });
            }
        };

        return eventSource;
    }

    useEffect(() => {
        fetchConvs();
        const eventSource = listenToConvs();
        
        return function cleanup() {
            eventSource.close();
        }
    }, []);

    return (
        <>
            { convs.map(c => (<Conv key={c.id} conv={c} />))}
        </>
    );
}

export default Convs;