import React from 'react';
import moment from 'moment';
import { Link } from 'react-router-dom/cjs/react-router-dom.min';

const Conv = React.memo(({ conv, deleteConv, loading }) => {

    return (
        <Link to={"/convs/"+conv.id} className="d-block conv card rounder shadow-lg p-2 m-1" style={{ textDecoration: 'none' }}>
            <div className="conv-header">
                    <img src={ conv.user.picture ? `/uploads/users/${conv.user.picture}` : '/build/images/default-avatar.jpeg'} 
                    alt={conv.user.name}  width="50" height="50" className="rounded-circle"/> 
                    <span className="font-weight-bolder text-success h6"> {conv.user.name} </span>
                <button disabled={loading} onClick={(e) => deleteConv(e, conv.id)} className="btn btn-sm delete-btn">
                    <i className="fas text-danger fa-trash"></i>
                </button>
            </div>
            <div className="conv-body">
                <div className="ml-2 text-primary">
                    { conv.msg.includes('data:image') ? <img src={conv.msg} width="100" height="100" /> : conv.msg }
                </div>
                <small className="text-muted">
                {   moment(new Date(conv.date)).from()  }
                </small>
            </div>
        </Link>
    );
});

export default Conv;