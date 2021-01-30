import React, { useRef } from "react";
import useFetch from "../hooks/useFetch";
import { new_msg_url } from "../urls";
import Loader from "../utils/loader";
import TextAreaField from "./field";
import Icon from "./icon";

const MsgForm = ({id}) => {
    const ref = useRef(null);
    const {loading, load}  =useFetch();

    const handleMsgSubmit = async (e) => {
        e.preventDefault();
        if (!ref.current.value) {
            return;
        }
        
        await load(new_msg_url(id), 'POST', ref.current.value);
        ref.current.value = '';
        ref.current.focus();
    }

    /**
     * @param {KeyboardEvent} e 
     */
    const handleKeyDown = e => {
        if (e.key === 'Enter') {
           handleMsgSubmit(e);
        }
    }

    return(
        <form onSubmit={handleMsgSubmit}> 
            <div className="row d-flex justify-content-center align-items-center">
                <div className="col-10">
                    {loading && <Loader
                        width= {50}
                        strokeWidth={10}
                        minHeight={10}
                    />}
                    {!loading && <TextAreaField 
                        ref={ref} 
                        name="msg" 
                        id="msg" 
                        handleKeyDown={handleKeyDown} 
                        placeholder="Type your message" 
                        required={true}
                        minLength={1}
                    />}
                </div>
                <div className="col-2 text-center">
                    <button disabled={loading} className="btn btn-sm">
                        <Icon icon="paper-plane text-primary" />    
                    </button>
                </div>
            </div>
        </form>
    );
}

export default MsgForm;