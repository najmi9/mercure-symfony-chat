import React, { useEffect, useRef, useState } from 'react';
import '../../styles/draggable-popup.css';

const DraggablePopUp = ({ children, trigger, style = {} }) => {
    const [state, setState] = useState('IDLE');
    const ref = useRef(null);
    const [position, setPosition] = useState({
        x: style.left,
        y: style.top,
    })

    const showPopup = (e) => {
        setState('SHOW');
        e.stopPropagation();

        document.body.onclick = (e) => {
            if (!e.path.includes(ref.current)) {
                setState('IDLE')
            }
        }
    }

    let offsetX, offsetY;

    const move = e => {
        ref.current.style.left = `${e.pageX - offsetX}px`
        ref.current.style.top = `${e.pageY - offsetY}px`
    }

    const remove = () => {
        setPosition({ x: ref.current.style.left, y: ref.current.style.top })
        ref.current.removeEventListener('mousemove', move)
    }

    const add = (e) => {
        offsetX = e.clientX - ref.current.getBoundingClientRect().left
        offsetY = e.clientY - ref.current.getBoundingClientRect().top
        ref.current.addEventListener('mousemove', move)
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
            {state === 'SHOW' && <div className="draggable-popup-box">
                <div ref={ref} className="daraggable-popup" style={{ top: position.y, left: position.x }} onMouseDown={add} onMouseUp={remove}>
                    {children}
                </div>
            </div>}
        </>
    )
}
export default DraggablePopUp;