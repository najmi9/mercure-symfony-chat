import React from 'react';
import { Link } from 'react-router-dom/cjs/react-router-dom.min';
import { userImage } from '../urls';
import Image from '../ui/image';
import Audio from '../ui/audio';
import fromNow from '../lib/moment';
import { userId } from '../utils/userData';

const Conv = React.memo(({ conv, deleteConv, loading }) => {

    let content = null;
    if (conv.msg?.includes('data:audio')) {
        content = <Audio src={conv.msg} style={{ width: 100 + '%', marginLeft: 0 }}/>;
    } else if (conv.msg.includes('data:image')) {
        content = <Image src={conv.msg} id={conv.id}/>;
    } else {
        content = conv.msg;
    }

    return (
        <Link to={"/convs/"+conv.id} className="d-block conv card rounder shadow-lg p-2 m-1" style={{ textDecoration: 'none' }}>
            <div className="conv-header">
                <img src={userImage(conv.user.picture)} 
                alt={conv.user.name}  width="50" height="50" className="rounded-circle"/> 
                <span className="font-weight-bolder text-success h6"> {conv.user.name} </span>

                {userId === conv.ownerId && <button disabled={loading} onClick={(e) => deleteConv(e, conv.id)} className="btn btn-sm delete-btn">
                    <svg width="15" height="25" viewBox="0 0 20 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.88639 9.33334H18.3205L17.338 25.0938C17.2931 25.8134 16.976 26.4887 16.4515 26.9822C15.927 27.4758 15.2344 27.7503 14.5149 27.75H5.69346C4.97396 27.7503 4.2814 27.4758 3.75687 26.9822C3.23234 26.4887 2.91529 25.8134 2.87032 25.0938L1.88639 9.33334ZM20 5.08334V7.91668H0.208344V5.08334H4.44942V3.66668C4.44942 2.91523 4.7473 2.19456 5.27754 1.66321C5.80777 1.13185 6.52693 0.833344 7.2768 0.833344H12.9316C13.6814 0.833344 14.4006 1.13185 14.9308 1.66321C15.4611 2.19456 15.7589 2.91523 15.7589 3.66668V5.08334H20ZM7.2768 5.08334H12.9316V3.66668H7.2768V5.08334Z" fill="#F33742"/>
                    </svg>
                </button>}

            </div>
            <div className="conv-body">
                <div className="ml-2 text-primary">
                    { content }
                </div>
                <small className="text-muted">
                    { fromNow(conv.date) }
                </small>
            </div>
        </Link>
    );
});

export default Conv;