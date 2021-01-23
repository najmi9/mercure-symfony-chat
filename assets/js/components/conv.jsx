import React, { useCallback } from 'react';
import Msgs from './msgs';
import ReactDOM from 'react-dom';
import moment from 'moment';

const Conv = React.memo(({ conv }) => {
    const handleConvClick = useCallback((id) => {
        const msgsContainer = document.querySelector('div.msgs');
        ReactDOM.render(<Msgs conv={id} />, msgsContainer);
        msgsContainer.scrollTop = msgsContainer.scrollHeight
    }, [conv]);

    return (
        <div onClick={() => handleConvClick(conv.id)} className="conv card rounder shadow-lg p-2 m-2">
            <div className="conv-header">
                <img src={conv.user.avatar} 
                alt={conv.user.name} className="rounded-circle"/> 
                <span className="font-weight-bolder text-success h6"> {conv.user.name} </span>
            </div>
            <div className="conv-body">
                <div className="ml-2 text-primary">{ conv.msg}</div>
                <small className="text-muted">
                {   moment(new Date(conv.date)).from()  }
                </small>
            </div>
        </div>
    );
});

export default Conv;