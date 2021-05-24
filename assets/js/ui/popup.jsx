import React, { useEffect, useRef, useState } from 'react';
import '../../styles/popup.css';

const PopUp = ({ children, trigger, style={}, closeStyle={}}) => {
    const [state, setState] = useState('IDLE');
    const ref = useRef(null);

    const showPopup = (e) => {
        setState('SHOW');
        e.stopPropagation();

        document.body.onclick = (e) => {
            if (!e.path.includes(ref.current)) {
                setState('IDLE')
            }
        }
    }

    const handleClose = () => {
        setState('IDLE')
    }

    useEffect(() => {
        return function cleanup() {
            document.body.removeEventListener('click', () => { });
        }
    })

    return (
        <>
            {state === 'IDLE' && <>
                <span onClick={showPopup}>{trigger}</span>
            </>}
            {state === 'SHOW' && <div className="popup-box">
                <div ref={ref} className="popup" style={style}>
                <div style={{position: 'relative'}}><span style={closeStyle} className="close-icon" onClick={handleClose}>x</span></div>
                    {children}
                </div>
            </div>}
        </>
    )
}
export default PopUp;