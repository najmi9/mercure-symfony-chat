import React, { useEffect, useRef, useState } from 'react';
import '../../styles/draggable-popup.css';

const DraggablePopUp = ({ children, trigger}) => {
    const [state, setState] = useState('IDLE');
    const ref = useRef(null);
    const boxRef = useRef(null);
  
    const showPopup = (e) => {
        setState('SHOW');
        e.stopPropagation();

        document.body.onclick = (e) => {
            if ((e.path && !e.path.includes(ref.current)) || e.target === boxRef.current) {
                setState('IDLE')
            }
        }
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
            {state === 'SHOW' && <div className="draggable-popup-box" ref={boxRef}>
                <div ref={ref} className="daraggable-popup">
                    {children}
                </div>
            </div>}
        </>
    )
}
export default DraggablePopUp;