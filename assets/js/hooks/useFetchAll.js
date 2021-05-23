import { useCallback, useState } from "react";
import { toast } from "react-toastify";
import jsonLdFetch from "../utils/jsonLdFetch";

const useFetchAll = () => {

    const [loading, setLoading] = useState(false);
    const [data, setData] = useState([]);
    const [count, setCount] = useState(0);

    const load = useCallback(async (url, forMsgs = false) => {
        setLoading(true);
        try {
            const res = await jsonLdFetch(url);
            setData(d => (forMsgs ? [...res.data, ...d] : [...d, ...res.data]));
            setCount(res.count);
            setLoading(false);
        } catch (error) {
            console.log(error);
            setLoading(false);
            toast.error('⚠️ Sorry, Unexpected Error, Refresh tour page and Try again.');
        }    
    }, []);

    return {
        loading,
        load, 
        data, 
        setData,
        count
    };

}

export default useFetchAll;