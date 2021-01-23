import React, { useRef } from "react";
import { new_msg_url } from "../urls";

const MsgForm = ({id}) => {
    const ref = useRef(null);

    const handleMsgSubmit = (e) => {
        e.preventDefault();
        if (!ref.current.value) {
            return;
        }
        
        fetch(new_msg_url(id), {
            method: 'POST',
            body: ref.current.value
        });
        ref.current.value = '';
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
        <div>
            <form onSubmit={handleMsgSubmit}> 
                <div className=" mt-2 row d-flex justify-content-center align-items-center">
                    <div className="col-9">
                        <textarea ref={ref} name="msg" id="msg" onKeyDown={handleKeyDown} className="form-control" 
                        placeholder="Type your message" />
                    </div>
                    <div className="col-3">
                        <button className="btn btn-sm"><i className="fas fa-paper-plane fa-2x text-primary"></i></button>
                    </div>
                </div>
            </form>
        </div>
    );
}

export default MsgForm;