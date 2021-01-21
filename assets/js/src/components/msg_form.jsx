import React, { useState } from "react";
import { new_msg_url } from "../urls";

const MsgForm = ({id}) => {
    const [msg, setMsg] = useState('');

    const handleMsgSubmit = (e) => {
        e.preventDefault();
        if (!msg) {
            return;
        }
        
        fetch(new_msg_url(id), {
            method: 'POST',
            body: msg
        });
        setMsg('');
    }

    const handleChange = ({currentTarget}) => {
        setMsg(currentTarget.value)
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
                <div className="form-group">
                    <textarea name="msg" id="msg" value={msg} onKeyDown={handleKeyDown} className="form-control" 
                    placeholder="Type your message" onChange={handleChange} />
                    <button className="btn btn-success">sned</button>
                </div>
            </form>
        </div>
    );
}

export default MsgForm;