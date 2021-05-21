import React, { useRef, useState } from "react";
import useFetch from "../hooks/useFetch";
import { new_msg_url } from "../urls";
import Loader from "../utils/loader";
import TextAreaField from "./field";
import Icon from "./icon";
import 'emoji-mart/css/emoji-mart.css'
import { Picker } from 'emoji-mart'
import '../../styles/msg_form.css';

const MsgForm = ({id}) => {
    const ref = useRef(null);
    const {loading, load}  =useFetch();
    const [showEmojis, setShowEmojis] = useState(false);

    const handleMsgSubmit = async (e) => {
        e.preventDefault();
        if (!ref.current.value) {
            return;
        }
        
        await load(new_msg_url(id), 'POST', ref.current.value);
        ref.current.value = '';
        ref.current.focus();
        setShowEmojis(false);
    }

    /**
     * @param {KeyboardEvent} e 
     */
    const handleKeyDown = e => {
        setShowEmojis(false);

        if (e.key === 'Enter') {
           handleMsgSubmit(e);
        }
    }

    const addEmoji = (e) => {
        ref.current.value += e.native;
    };

    return(
        <div style={{position: 'relative'}}>
            <form onSubmit={handleMsgSubmit}> 
                <div className="row">
                    <div className="col-9">
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
                            rows={2}
                        />}
                    </div>
                    <div className="col-3">
                        <div className="mt-4">
                            <button type="button" 
                            disabled={loading} onClick={() => setShowEmojis(!showEmojis)} 
                            className="btn btn-lg">
                                <Icon icon="grin-beam text-warning" />
                            </button>

                            <input disabled={loading} type="file" className="btn btn-lg"/>

                            <button disabled={loading} type="submit" className="btn btn-lg">
                                <Icon icon="paper-plane text-primary" />
                            </button>
                        </div>
                        
                    </div>
                </div>
            </form>

            {showEmojis && <div className="emojis">
                <Picker onSelect={addEmoji} />
            </div>}
        </div>
    );
}

export default MsgForm;