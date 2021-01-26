import React, { useEffect } from 'react';
import Conv from './conv';
import { convs_url, convTopic } from '../urls';
import useFetch from '../hooks/useFetch';
import Loader from '../utils/loader';
import useEventSource from '../hooks/useEventSource';

const Convs = () => {
    const userId = parseInt(document.querySelector('div.data').dataset.user);
    const [loading, load, convs, setConvs] = useFetch(convs_url);
    const [eventSource] = useEventSource(setConvs, convTopic(userId), true);

    useEffect(() => {
        load();
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