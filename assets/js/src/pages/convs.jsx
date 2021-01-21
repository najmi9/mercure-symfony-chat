import React, { useEffect, useRef, useState } from 'react';
import Conv from '../components/conv';

const Convs = () => {
    const [convs, setConvs] = useState([]);
    const divRef= useRef(null);
   
    const fetchConvs = async () => {
        const r = await fetch('/conversations');
        const res = await r.json();
        setConvs(res);
    }

    const listenToConvs = () => {
        const url = new URL('http://localhost:3000/.well-known/mercure');
        url.searchParams.append('topic', 'http://mywebsite.com/convs');
        const eventSource = new EventSource(url, { withCredentials: true });

        eventSource.onmessage = e => {
            const data = JSON.parse(e.data);
            if (data.new) {
                setConvs(convs => [...convs, data]);
            } else {
                // update the conv to the top
                // update his content.
            }
            
        };
    }

    useEffect(() => {
        fetchConvs();
        listenToConvs();  
    }, [])

    return (
        <div className="convs" ref={divRef}>
            {console.log(convs)}
            { convs.map(c => (<Conv key={c.id} conv={c} />))}
        </div>
    );
}

export default Convs;