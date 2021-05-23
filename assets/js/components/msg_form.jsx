import React, { useEffect, useRef, useState } from "react";
import useFetch from "../hooks/useFetch";
import { edit_msg_url, new_msg_url } from "../urls";
import Loader from "../utils/loader";
import TextAreaField from "../ui/field";
import Icon from "../ui/icon";
import 'emoji-mart/css/emoji-mart.css'
import '../../styles/msg_form.css';
import '../../styles/file_upload.css';
import PickerEmoji from "../ui/EmojiPicker";
import Image from "../ui/image";
import FileUpload from "./fileUpload";
import AudioUpload from "./audioUpload";

const MsgForm = ({id, msg={}, onUpdate=null}) => {
    const ref = useRef(null);
    const {loading, load}  =useFetch();
    const [showEmojis, setShowEmojis] = useState(false);

    const handleMsgSubmit = async (e) => {
        e.preventDefault();

        if (!ref.current.value) {
            return;
        }

       await postData(ref.current.value);
    }

    const postData = async (data) => {
        const method = msg.id ? 'PUT' : 'POST';

        const url = msg.id ? edit_msg_url(msg.id) : new_msg_url(id);

        const newMsg = await load(url, method, data);

        if (onUpdate) {
            onUpdate(msg, newMsg);
        } else {
            ref.current.value = '';
            ref.current.focus();
            setShowEmojis(false); 
        }
    }

    const handleKeyDown = e => {
        setShowEmojis(false);

        if (e.key === 'Enter') {
           handleMsgSubmit(e);
        }
    }

    const addEmoji = (e) => {
        ref.current.value += e.native;
    };

    useEffect(() => {
        if (msg && msg.content && ref.current) {
            ref.current.value = msg.content;
        }
    }, [msg, ref]);

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
                        {!loading && (msg.content && msg.content.includes('image') 
                            ? <Image src={msg.content} id={msg.id}/>
                            :<TextAreaField 
                                ref={ref} 
                                name="msg" 
                                id="msg" 
                                handleKeyDown={handleKeyDown} 
                                placeholder="Type your message" 
                                required={true}
                                minLength={1}
                                rows={2}
                                onFocus={() => setShowEmojis(false) }
                                defaultValue={msg.content}
                            />)
                        }
                    </div>
                    <div className="col-3  d-flex justify-content-center align-items-center">
                        <div>
                            {!msg.content?.includes('image') && <button type="button" disabled={loading} onClick={() => setShowEmojis(!showEmojis)} 
                            className="btn border">
                                <Icon icon="grin-beam text-warning" />
                            </button>}

                            <FileUpload loading={loading} postData={postData}/>

                            {!msg.content?.includes('image') && <button disabled={loading} type="submit" className="btn border">
                                <Icon icon="paper-plane text-primary" />
                            </button>}

                            { navigator.mediaDevices && <AudioUpload  loading={loading} postData={postData}/>}
                        </div>
                    </div>
                </div>
            </form>
            {showEmojis && <PickerEmoji addEmoji={addEmoji} />}
        </div>
    );
}



export default MsgForm;