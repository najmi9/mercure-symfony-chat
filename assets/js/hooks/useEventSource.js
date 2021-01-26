import { hub_url } from "../urls";


const useEventSource = (setItems, topic, isForConvs = false) => {
    const url = new URL(hub_url);
    url.searchParams.append('topic', topic);
    const eventSource = new EventSource(url, { withCredentials: true });

    /**
     * @param {MessageEvent} e 
     */
    eventSource.onmessage = e => {
        if (isForConvs) {
            const userId = parseInt(document.querySelector('div.data').dataset.user);
            const conv = JSON.parse(e.data);
            conv['user'] = conv.users.filter(u => u.id !== userId)[0]; 
            delete conv['users'];
            if (conv.new) {
                setItems(convs => [conv, ...convs]);
            } else {
                setItems(convs => {
                    const oldConvs = [...convs];
                    const oldData = {...oldConvs.filter(e => e.id === conv.id)[0]};
                    oldData.msg = conv.msg; 
                    oldData.date = conv.date;
                    const cs = oldConvs.filter(c => c.id !== conv.id);
                    cs.splice(0, 0, oldData);
                    return cs;
                });
            }
        } else {
            const data = JSON.parse(e.data);
            document.querySelector('div.msgs').scrollTop = document.querySelector('div.msgs').scrollHeight; 
            setItems(items => [...items, data]);
        }
    };

    return [
        eventSource
    ];
}

export default useEventSource;