import React, { useEffect, useState } from 'react';
import Conv from '../components/conv';

const Convs = () => {
    const [convs, setConvs] = useState([]);
    useEffect(() => {
        fetch('/conversations')
        .then(r => r.json())
        .then(res => setConvs(res))
    }, [])

    return(
        <div className="convs">
            { convs.map(c => (<Conv key={c.id} conv={c} />)) }
        </div>
    );
}

export default Convs;