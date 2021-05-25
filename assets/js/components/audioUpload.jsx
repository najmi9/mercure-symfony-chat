import React, { useRef, useState } from 'react';
import { toast } from 'react-toastify';

const AudioUpload = ({loading, postData}) => {
    const [recorder, setRecorder] = useState(null);

    const ref = useRef(null);

    const onPointerDown = async () => {
        try {
            const constraints = { audio: true, video: false };
            const chunks = [];
            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            const mediaRecorder = new MediaRecorder(stream); 
            mediaRecorder.ondataavailable = event => chunks.push(event.data)
            mediaRecorder.start();

            ref.current.classList.add('recording');

            mediaRecorder.onstop = async e => {
                const blob = new Blob(chunks, { 'type': 'audio/ogg; codecs=opus' });
                const audioFile = new File([blob], 'audio.ogg', {
                    type: 'audio/ogg; codecs="opus"',
                });

                if (audioFile.size > 2000000) { // 2M
                    toast.error('Audio Size Must Be Less Than 2M.')
                    return;
                }

                const reader = new FileReader();
                    reader.onload = async function (e) {
                        await postData(e.target.result);
                    }
                    reader.readAsDataURL(audioFile);
            }
            setRecorder(mediaRecorder);
        } catch (error) {
            ref.current.classList.remove('recording');
            console.log(error);
            toast.error(error);
        }
    }

    const onPointerUp = async () => {
        ref.current.classList.remove('recording');
        if (recorder) {
            recorder.stop();
        }
    }

    return (
        <button disabled={loading} ref={ref} className="btn border" onPointerUp={onPointerUp} onPointerDown={onPointerDown}>
            <svg width="25" height="25" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 24C21.9 24 25 20.9 25 17V9C25 5.1 21.9 2 18 2C14.1 2 11 5.1 11 9V17C11 20.9 14.1 24 18 24Z" fill="black"/>
            <path d="M30 17H28C28 22.5 23.5 27 18 27C12.5 27 8 22.5 8 17H6C6 23.3 10.8 28.4 17 28.9V32H14C13.4 32 13 32.4 13 33C13 33.6 13.4 34 14 34H22C22.6 34 23 33.6 23 33C23 32.4 22.6 32 22 32H19V28.9C25.2 28.4 30 23.3 30 17Z" fill="black"/>
            </svg>
        </button>
    );
}

export default AudioUpload;
