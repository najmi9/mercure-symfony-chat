import { useState } from "react";

const useFetch = (url, method='GET') => {

    const [loading, setLoading] = useState(false);
    const [data, setData] = useState([]);

    const load = async (body = null) => {
        const headers = new Headers();
        headers.append("Content-Type", "text/plain");
        headers.append("Accept", "application/json");
        
        const options = {
            method: method,
            headers: headers,
            body: body
        };

        setLoading(true);
        const r = await fetch(url, options);
        const res = await r.json();
        setLoading(false);
        setData(res);
        return res;
    }
    return [
        loading,
        load, 
        data, 
        setData
    ];

}

export default useFetch;