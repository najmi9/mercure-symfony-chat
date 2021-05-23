import React, { useRef, useState } from 'react';

const AudioUpload = ({loading, postData}) => {
    const [recorder, setRecorder] = useState(null);

    const ref = useRef(null);

    const onPointerDown = async () => {
        const constraints = { audio: true, video: false };
        const chunks = [];

        const stream = await navigator.mediaDevices.getUserMedia(constraints);

        const mediaRecorder = new MediaRecorder(stream); 

        mediaRecorder.ondataavailable = event => chunks.push(event.data); 

        mediaRecorder.start();

        ref.current.style.backgroundColor = 'green';

        mediaRecorder.onstop = async e => {
            const blob = new Blob(chunks, { 'type': 'audio/ogg; codecs=opus' });

            const audioFile = new File([blob], 'audio.ogg', {
                type: 'audio/ogg; codecs="opus"',
            });

            const reader = new FileReader();
                reader.onload = async function (e) {
                    await postData(e.target.result);
                }
                reader.readAsDataURL(audioFile);
        }

        setRecorder(mediaRecorder);
    }

    const onPointerUp = async () => {
        ref.current.style.backgroundColor = '';

        if (recorder) {
            recorder.stop();
        }
    }

    return (
        <button disabled={loading} ref={ref} className="btn border" onPointerUp={onPointerUp} onPointerDown={onPointerDown}>
            <i className="fas fa-microphone text-secondary"></i>
        </button>
    );
}

export default AudioUpload;
