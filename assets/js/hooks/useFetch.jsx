import { useCallback, useState } from "react";
import { toast } from "react-toastify";

const useFetch = (url, method='GET') => {

    const [loading, setLoading] = useState(false);
    const [data, setData] = useState([]);

    const load = useCallback(async (body = null) => {
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
            if (r.ok) {
                const res = await r.json();
                setLoading(false);
                setData(res);
                return res;
            } else {
                setLoading(false);
                toast.error('⚠️ Sorry, Unexpected Error, Refresh tour page and Try again.');
            }

       
    }, [url, method]);

    return {
        loading,
        load, 
        data, 
        setData
    };

}

export default useFetch;