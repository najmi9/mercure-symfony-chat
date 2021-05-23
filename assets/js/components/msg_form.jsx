import React, { useEffect, useRef, useState } from 'react';
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

        await load(url, method, data);

        if (onUpdate) {
            onUpdate();
        } else {
            ref.current.value = '';
            ref.current.focus();
            setShowEmojis(false); 
        }
    }

    const showEmojisToggle = () => {
        console.log('test');
        setShowEmojis(!showEmojis);
    }

    const handleKeyDown = e => {
        setShowEmojis(false);

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
                                onFocus={() => setShowEmojis(false) }
                                defaultValue={msg.content}
                            />)
                        }
                    </div>
                    <div className='col-3  d-flex justify-content-center align-items-center'>
                        <div>
                            {!msg.content?.includes('image') && <button type='button' disabled={loading} onClick={showEmojisToggle} 
                            className='btn border'>
                                <svg width="25" height="25" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M36 18C36 27.941 27.941 36 18 36C8.06 36 0 27.941 0 18C0 8.06 8.06 0 18 0C27.941 0 36 8.06 36 18Z" fill="#FFCC4D"/>
                                <path d="M16 18C15.581 18 15.191 17.735 15.051 17.316C14.848 16.717 14.034 15 13 15C11.938 15 11.112 16.827 10.949 17.316C10.9087 17.4422 10.8438 17.5591 10.758 17.66C10.6722 17.7609 10.5672 17.8437 10.4492 17.9037C10.3311 17.9636 10.2023 17.9996 10.0702 18.0093C9.93811 18.0191 9.80541 18.0026 9.67978 17.9606C9.55415 17.9187 9.4381 17.8523 9.33837 17.7651C9.23863 17.678 9.1572 17.5719 9.0988 17.4531C9.04039 17.3342 9.00618 17.2049 8.99815 17.0727C8.99011 16.9405 9.00842 16.8081 9.052 16.683C9.177 16.307 10.356 13 13 13C15.644 13 16.823 16.307 16.949 16.684C16.9991 16.8343 17.0127 16.9944 16.9888 17.151C16.9648 17.3077 16.904 17.4564 16.8113 17.5849C16.7187 17.7134 16.5968 17.8181 16.4557 17.8903C16.3146 17.9624 16.1585 18 16 18V18ZM26 18C25.7902 18 25.5857 17.9339 25.4155 17.8111C25.2453 17.6884 25.1182 17.5151 25.052 17.316C24.849 16.717 24.033 15 23 15C21.938 15 21.111 16.827 20.948 17.316C20.8615 17.5643 20.6807 17.7685 20.4447 17.8844C20.2087 18.0004 19.9366 18.0187 19.6872 17.9355C19.4378 17.8523 19.2312 17.6742 19.1121 17.4398C18.993 17.2054 18.9711 16.9335 19.051 16.683C19.177 16.307 20.355 13 23 13C25.645 13 26.823 16.307 26.948 16.684C26.998 16.8343 27.0117 16.9942 26.9878 17.1508C26.9639 17.3074 26.9032 17.456 26.8106 17.5845C26.7181 17.713 26.5963 17.8177 26.4554 17.8899C26.3144 17.9621 26.1584 17.9999 26 18V18ZM18 22C14.377 22 11.973 21.578 9 21C8.321 20.869 7 21 7 23C7 27 11.595 32 18 32C24.404 32 29 27 29 23C29 21 27.679 20.868 27 21C24.027 21.578 21.623 22 18 22Z" fill="#664500"/>
                                <path d="M9 23C9 23 12 24 18 24C24 24 27 23 27 23C27 23 25.656 29.75 18 29.75C10.344 29.75 9 23 9 23Z" fill="white"/>
                                <path d="M18 27.594C14.404 27.594 11.728 27.222 10.063 26.849L9.23801 24.978C10.061 25.29 13.127 25.875 18.001 25.875C22.955 25.875 26.038 25.259 26.865 24.937L26.164 26.779C24.53 27.159 21.745 27.594 18 27.594Z" fill="black"/>
                                </svg>
                            </button>}

                            <FileUpload loading={loading} postData={postData}/>

                            {!msg.content?.includes('image') && <button disabled={loading} type='submit' className='btn border'>
                                <svg width="25" height="25" viewBox="0 0 32 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M31.2624 0.246802C31.0202 0.107704 30.7392 0.0242047 30.4472 0.00452132C30.1551 -0.0151621 29.862 0.0296481 29.5967 0.134543L0.966257 11.5026C0.673599 11.6189 0.426855 11.8039 0.255531 12.0355C0.0842062 12.267 -0.00444704 12.5353 0.000171698 12.8083C0.00479044 13.0812 0.102486 13.3473 0.281576 13.5746C0.460665 13.8019 0.713569 13.9809 1.01005 14.0902L10.1044 17.448V27L19.9331 21.0773L27.9564 24.0386C28.2041 24.1302 28.4743 24.17 28.7441 24.1549C29.0139 24.1397 29.2754 24.0699 29.5065 23.9514C29.7376 23.8329 29.9315 23.6693 30.0718 23.4742C30.2121 23.2791 30.2947 23.0584 30.3125 22.8308L31.9966 1.51576C32.0151 1.26868 31.9571 1.02181 31.8284 0.799345C31.6997 0.576882 31.5046 0.386474 31.2624 0.246802V0.246802ZM27.1093 20.624L18.2355 17.3471L23.5776 8.52698L10.6956 14.5662L5.75765 12.7431L28.4448 3.73394L27.1093 20.624V20.624Z" fill="black"/>
                                </svg>
                            </button>}

                            {navigator.mediaDevices && <AudioUpload  loading={loading} postData={postData}/>}
                        </div>
                    </div>
                </div>
            </form>

            {showEmojis && <PickerEmoji addEmoji={addEmoji}/>}
        </div>
    );
}



export default MsgForm;