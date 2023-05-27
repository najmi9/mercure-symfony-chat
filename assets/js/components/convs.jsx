import React, { useCallback, useEffect, useState } from 'react';
import Conv from './conv';
import { convs_url, convTopic, delete_conv, hub_url } from '../urls';
import useFetchAll from '../hooks/useFetchAll';
import Loader from '../utils/loader';
import useFetch from '../hooks/useFetch';
import notify from '../utils/notify';
import { userId } from '../utils/userData';

const Convs = () => {
    const {loading, load, data: convs, setData: setConvs, count} = useFetchAll();
    const {loading: deleteLoading, load: deleteLoad} = useFetch();
    const [page, setPage] = useState(1);
    const max = 7;

    const listenToMercure = useCallback(() => {
        const url = new URL(hub_url);
        url.searchParams.append('topic', convTopic(userId));
        const eventSource = new EventSource(url, { withCredentials: true });
        // add the possibility to read missed messages after being offline for a while.
        const lastEventId = localStorage.getItem('lastEventIdConv');

        if (lastEventId) {
            url.searchParams.append('Last-Event-ID', lastEventId);
        }

        eventSource.onmessage = e => {
            const conv = JSON.parse(e.data);
            localStorage.setItem('lastEventIdConv', e.lastEventId);

            if (conv.isDeleted) {
                setConvs(convs => convs.filter(c => c.id !== conv.id));
                return;
            }

            if (conv.ownerId !== userId) {
                notify();
            }

            conv['user'] = conv.users.filter(u => u.id !== userId)[0];
            delete conv['users'];
            if (conv.new) {
                setConvs(convs => [conv, ...convs]);
            } else {
                // get the conversation and update his last message and push it to the top.
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
        const yes = window.confirm('Are you sure you want to delete this conversation?');
        if (!yes) {
            return;
        }
        await deleteLoad(delete_conv(id), 'DELETE');
    }, []);

    useEffect(() => {
        load(`${convs_url}?page=${page}&max=${max}`);
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
                    <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.5 0.875C8.7942 0.875 5.75103 3.71215 5.42489 7.33333H5.23437C3.9069 7.33333 2.63381 7.86067 1.69515 8.79933C0.756489 9.73798 0.229156 11.0111 0.229156 12.3385C0.229156 13.666 0.756489 14.9391 1.69515 15.8778C2.63381 16.8164 3.9069 17.3438 5.23437 17.3438H9.9722C10.4521 13.1633 14.0035 9.91667 18.3125 9.91667C20.8913 9.91667 23.1989 11.0792 24.7385 12.9088C24.8189 12.208 24.7502 11.498 24.537 10.8256C24.3238 10.1532 23.9708 9.53339 23.5012 9.00693C23.0317 8.48047 22.4561 8.05922 21.8123 7.77079C21.1685 7.48237 20.4711 7.33329 19.7656 7.33333H19.5751C19.249 3.71215 16.2058 0.875 12.5 0.875ZM11.2083 18.3125C11.2083 22.2359 14.3891 25.4167 18.3125 25.4167C22.2359 25.4167 25.4167 22.2359 25.4167 18.3125C25.4167 14.3891 22.2359 11.2083 18.3125 11.2083C14.3891 11.2083 11.2083 14.3891 11.2083 18.3125ZM16.7928 13.7316C17.6062 13.4413 18.4857 13.3901 19.3273 13.584C20.1689 13.7779 20.9373 14.2088 21.5417 14.8256V14.4375C21.5417 14.2662 21.6097 14.1019 21.7308 13.9808C21.8519 13.8597 22.0162 13.7917 22.1875 13.7917C22.3588 13.7917 22.523 13.8597 22.6442 13.9808C22.7653 14.1019 22.8333 14.2662 22.8333 14.4375V16.375C22.8333 16.5463 22.7653 16.7106 22.6442 16.8317C22.523 16.9528 22.3588 17.0208 22.1875 17.0208H20.25C20.0787 17.0208 19.9144 16.9528 19.7933 16.8317C19.6722 16.7106 19.6042 16.5463 19.6042 16.375C19.6042 16.2037 19.6722 16.0394 19.7933 15.9183C19.9144 15.7972 20.0787 15.7292 20.25 15.7292H20.6181C20.3194 15.4244 19.9631 15.1819 19.5701 15.0157C19.177 14.8495 18.7549 14.7628 18.3282 14.7607C17.9014 14.7586 17.4785 14.8411 17.0838 15.0035C16.6892 15.1658 16.3306 15.4048 16.0288 15.7066C15.9076 15.8277 15.7433 15.8957 15.572 15.8956C15.4007 15.8955 15.2364 15.8274 15.1153 15.7062C14.9942 15.5851 14.9262 15.4207 14.9263 15.2494C14.9263 15.0781 14.9944 14.9138 15.1156 14.7927C15.5892 14.3191 16.1621 13.9567 16.7928 13.7316V13.7316ZM19.8321 22.8934C19.0188 23.1837 18.1393 23.2349 17.2977 23.041C16.4561 22.8471 15.6877 22.4162 15.0833 21.7994V22.1875C15.0833 22.3588 15.0153 22.5231 14.8942 22.6442C14.773 22.7653 14.6088 22.8333 14.4375 22.8333C14.2662 22.8333 14.1019 22.7653 13.9808 22.6442C13.8597 22.5231 13.7917 22.3588 13.7917 22.1875V20.25C13.7917 20.0787 13.8597 19.9144 13.9808 19.7933C14.1019 19.6722 14.2662 19.6042 14.4375 19.6042H16.375C16.5463 19.6042 16.7105 19.6722 16.8317 19.7933C16.9528 19.9144 17.0208 20.0787 17.0208 20.25C17.0208 20.4213 16.9528 20.5856 16.8317 20.7067C16.7105 20.8278 16.5463 20.8958 16.375 20.8958H16.0069C16.4383 21.3365 16.9869 21.6443 17.5878 21.7828C18.1887 21.9213 18.8167 21.8847 19.3975 21.6773C19.8484 21.5163 20.2578 21.2571 20.5962 20.9184C20.7173 20.7973 20.8817 20.7293 21.053 20.7294C21.2243 20.7295 21.3886 20.7976 21.5097 20.9188C21.6308 21.0399 21.6988 21.2043 21.6987 21.3756C21.6987 21.5469 21.6306 21.7112 21.5094 21.8323C21.0358 22.3059 20.4629 22.6683 19.8321 22.8934Z" fill="#FFFCFC"/>
                    </svg> load more
                </button>
            </div>}
        </>
    );
}

export default Convs;
