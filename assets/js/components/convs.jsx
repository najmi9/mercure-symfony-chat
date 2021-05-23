import React, { useCallback, useEffect, useState } from 'react';
import Conv from './conv';
import { convs_url, convTopic, delete_conv, hub_url } from '../urls';
import useFetchAll from '../hooks/useFetchAll';
import Loader from '../utils/loader';
import useFetch from '../hooks/useFetch';
import notify from '../utils/notify';

const Convs = () => {
    const userId = parseInt(document.querySelector('div.data').dataset.user);
    const {loading, load, data: convs, setData: setConvs, count} = useFetchAll();
    const {loading: deleteLoading, load: deleteLoad} = useFetch();
    const [page, setPage] = useState(1);
    const max = 4;

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
            const conv = JSON.parse(e.data);
            if (conv.ownerId !== userId) {
                notify();
            }

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

    const deleteConv = useCallback( async (e, id) => {
        e.preventDefault();
        const yes = window.confirm('Are you sure?');
        if (!yes) {
            return;
        }
        await deleteLoad(delete_conv(id), 'DELETE');
        setConvs(convs => convs.filter(e => e.id !== id));
    }, []);

    useEffect(async () => {
        await load(`${convs_url}?page=${page}&max=${max}`);
        const eventSource = listenToMercure();
        return function cleanup() {
            eventSource.close();
        }
    }, [page]);

    return (
        <>
            { loading && (<Loader />)}
            { !loading &&  (convs.map(c => (<Conv key={c.id} conv={c} loading={deleteLoading} deleteConv={deleteConv} />)))}

            {!loading &&  count > page * max && <div className="box text-center">
                <button className="btn btn-primary" onClick={() => setPage(page + 1)}>
                    <i className={"fas fa-sync-alt"}></i> load more
                </button>
            </div>}
        </>
    );
}

export default Convs;