import React from 'react';
import moment from 'moment';
import { Link } from 'react-router-dom/cjs/react-router-dom.min';

const Conv = React.memo(({ conv, deleteConv, loading }) => {

    return (
        <div className="conv card rounder shadow-lg p-2 m-1">
            <div className="conv-header">
                <Link to={"/convs/"+conv.id} className="link-to-conv">
                    <img src={conv.user.avatar} 
                    alt={conv.user.name} className="rounded-circle"/> 
                    <span className="font-weight-bolder text-success h6"> {conv.user.name} </span>
                </Link>
                <button disabled={loading} onClick={(e) => deleteConv(e, conv.id)} className="btn btn-sm delete-btn"><i className="fas text-danger fa-trash"></i></button>
            </div>
            <Link to={"/convs/"+conv.id} className="link-to-conv">
                <div className="conv-body">
                    <div className="ml-2 text-primary">{ conv.msg}</div>
                    <small className="text-muted">
                    {   moment(new Date(conv.date)).from()  }
                    </small>
                </div>
            </Link>
        </div>
    );
});

export default Conv;