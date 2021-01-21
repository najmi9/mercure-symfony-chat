import React, { useEffect, useState } from 'react';
import Conv from '../components/conv';
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
            const data = JSON.parse(e.data);
            // If the conv already exsits we updated to be the first.
            // else we order
            if (data.new) {
                setConvs(convs => [data, ...convs]);
                console.log(data.user);
            } else {
                setConvs(convs => {
                    const oldConvs = [...convs];
                    const oldData = oldConvs.filter(e => e.id === data.id)[0];
                    oldData.msg = data.msg; oldData.date = data.date;
                    const index = oldConvs.findIndex(e => e.id == data.id);
                    oldConvs.splice(index, 1); oldConvs.splice(0, 0, oldData);
                    return oldConvs
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