import React from 'react';
import moment from 'moment';

const Msg = ({ msg}) => (
    <div key={msg.id} id="msg">
        <div className={msg.user.email !== msg.otherUserId ? 'not_my_msg' : 'my_msg'}>
            <div>
                <img src={msg.user.avatar} alt={msg.user.name} width="30" height="30" className="rounded-circle" />
                <span className="user_name"> { msg.user.name } </span>
                <small className="text-muted">
                    {moment(new Date(msg.updated)).from()}
                </small>
            </div>
            <div className="msg-text">
                {msg.content}
            </div>
        </div>
    </div>
);

export default Msg;