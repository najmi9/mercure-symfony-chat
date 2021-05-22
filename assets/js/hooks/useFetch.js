import { useCallback, useState } from "react";
import { toast } from "react-toastify";
import jsonLdFetch from "../utils/jsonLdFetch";

const useFetch = () => {
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState({});

    const load = useCallback(async (url, method = 'POST', body = null) => {
        setLoading(true);
        try {
            const res = await jsonLdFetch(url, method, body);
            setData(res);
            setLoading(false);
            return res;
        } catch (error) {
            setLoading(false);
            toast.error('⚠️ Sorry, Unexpected Error, Refresh tour page and Try again.');
        }
    }, []);

    return {
        loading,
        load,
        data
    };

}

export default useFetch;