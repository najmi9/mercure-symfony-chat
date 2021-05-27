import React, { useEffect, useRef } from 'react';
import useFetch from '../hooks/useFetch';
import { edit_msg_url, new_msg_url } from '../urls';
import Loader from '../utils/loader';
import TextAreaField from '../ui/field';
import 'emoji-mart/css/emoji-mart.css'
import '../../styles/msg_form.css';
import '../../styles/file_upload.css';
import PickerEmoji from '../ui/EmojiPicker';
import Image from '../ui/image';
import FileUpload from './fileUpload';
import AudioUpload from './audioUpload';

const MsgForm = ({id, msg={}, onUpdate=null}) => {
    const ref = useRef(null);
    const {loading, load}  =useFetch();

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

        await load(url, method, data);

        if (onUpdate) {
            onUpdate();
        } else {
            ref.current.value = '';
            ref.current.focus();
        }
    }

    const handleKeyDown = e => {
        if (e.key === 'Enter') {
           handleMsgSubmit(e);
        }
    }

    const addEmoji = (e) => ref.current.value += e.native;

    useEffect(() => {
        if (msg && msg.content && ref.current) {
            ref.current.value = msg.content;
        }
    }, [msg, ref]);

    return(
        <div style={{position: 'relative'}}>
            <form onSubmit={handleMsgSubmit}> 
                <div className='row'>
                    <div className='col-9'>
                        {loading && <Loader
                            width= {50}
                            strokeWidth={10}
                            minHeight={10}
                        />}
                        {!loading && (msg.content && msg.content.includes('image') 
                            ? <Image src={msg.content} id={msg.id}/>
                            :<TextAreaField 
                                ref={ref} 
                                name='msg' 
                                id='msg' 
                                handleKeyDown={handleKeyDown} 
                                placeholder='Type your message' 
                                required={true}
                                minLength={1}
                                rows={2}
                                defaultValue={msg.content}
                            />)
                        }
                    </div>
                    <div className='col-3  d-flex justify-content-center align-items-center'>
                        <div>
                            {!msg.content?.includes('image') && <button disabled={loading} type='submit' className='btn border'>
                                <svg width="25" height="25" viewBox="0 0 32 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M31.2624 0.246802C31.0202 0.107704 30.7392 0.0242047 30.4472 0.00452132C30.1551 -0.0151621 29.862 0.0296481 29.5967 0.134543L0.966257 11.5026C0.673599 11.6189 0.426855 11.8039 0.255531 12.0355C0.0842062 12.267 -0.00444704 12.5353 0.000171698 12.8083C0.00479044 13.0812 0.102486 13.3473 0.281576 13.5746C0.460665 13.8019 0.713569 13.9809 1.01005 14.0902L10.1044 17.448V27L19.9331 21.0773L27.9564 24.0386C28.2041 24.1302 28.4743 24.17 28.7441 24.1549C29.0139 24.1397 29.2754 24.0699 29.5065 23.9514C29.7376 23.8329 29.9315 23.6693 30.0718 23.4742C30.2121 23.2791 30.2947 23.0584 30.3125 22.8308L31.9966 1.51576C32.0151 1.26868 31.9571 1.02181 31.8284 0.799345C31.6997 0.576882 31.5046 0.386474 31.2624 0.246802V0.246802ZM27.1093 20.624L18.2355 17.3471L23.5776 8.52698L10.6956 14.5662L5.75765 12.7431L28.4448 3.73394L27.1093 20.624V20.624Z" fill="black"/>
                                </svg>
                            </button>}
                        
                            {!msg.content?.includes('image') && <PickerEmoji loading={loading} addEmoji={addEmoji}/>}

                            <FileUpload loading={loading} postData={postData}/>

                            {navigator.mediaDevices && <AudioUpload  loading={loading} postData={postData}/>}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    );
}



export default MsgForm;