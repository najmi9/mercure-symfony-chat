import React, { useState } from 'react';
import moment from 'moment';
import Image from './image';
import useFetch from '../hooks/useFetch';
import Loader from '../utils/loader';
import MsgForm from './msg_form';

const Msg = React.memo(({ msg, userId, setMsgs, conv}) => {
    const {load: deleteMsg, loading: deleteLoading} = useFetch();

    const [state, setState] = useState('IDLE');

    const handleDelete = async () => {
        await deleteMsg(`/api/messages/${msg.id}/delete`, 'DELETE');
        setMsgs(msgs => msgs.filter(m => m !== msg));
    }

    const onUpdate = async (oldMsg, newMsg) => {
        setMsgs(msgs => msgs.map(m => m === oldMsg ? newMsg : m))
        setState('IDLE');
    }

    const mine = id => userId === id;

    return(
        <div id="msg">
            {state === 'IDLE' && <>
                {!deleteLoading  && <div className={mine(msg.user.id) ? 'my_msg' : 'not_my_msg'}>
                    <img src={msg.user.picture ? `/uploads/users/${msg.user.picture}` : '/build/images/default-avatar.jpeg'} alt={msg.user.name}
                    width="30" height="30" className="rounded-circle" />

                    <span className="user_name"> { msg.user.name } </span>

                    <small className="text-muted">
                        {moment(new Date(msg.updatedAt)).from()}
                    </small>

                    {mine(msg.user.id) && <div className="btn-group dropright">
                        <button type="button" className="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i className="fas fa-ellipsis-v"></i>
                        </button>
                        <div className="dropdown-menu">
                            <button className="dropdown-item" type="button" onClick={handleDelete}><i className="fas fa-trash-alt"></i></button>
                            <button className="dropdown-item" type="button" onClick={() => setState('EDIT')}><i className="fas fa-pen-alt"></i></button>
                        </div>
                    </div>}

                    <div className="msg-text">
                        { msg.content.includes('data:image')
                        ? <Image src={msg.content} id={msg.id}/>
                        : msg.content }
                    </div>
                </div>}
                {deleteLoading && <Loader width={60} minHeight={60} strokeWidth={7}/>}
            </>}

            {state === 'EDIT' && <MsgForm  id={conv} msg={msg} onUpdate={onUpdate}/>}

        </div>
    );
});


export default Msg;