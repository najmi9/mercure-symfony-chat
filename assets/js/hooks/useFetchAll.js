import { useCallback, useState } from "react";
import { toast } from "react-toastify";
import jsonLdFetch from "../utils/jsonLdFetch";

const useFetchAll = (url, method='GET') => {

    const [loading, setLoading] = useState(false);
    const [data, setData] = useState([]);

    const load = useCallback(async () => {
        setLoading(true);
        try {
            const res = await jsonLdFetch(url, method);
            setData(res);
            setLoading(false);
        } catch (error) {
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

export default useFetchAll;