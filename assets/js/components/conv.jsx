import React from 'react';
import moment from 'moment';
import { Link } from 'react-router-dom/cjs/react-router-dom.min';

const Conv = React.memo(({ conv }) => {

    return (
        <div className="conv card rounder shadow-lg p-2 m-2">
           <Link to={"/convs/"+conv.id} className="link-to-conv">
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
           </Link>
        </div>
    );
});

export default Conv;