import moment from 'moment';
import React, { useEffect } from 'react';
import useFetch from '../hooks/useFetch';
import { conv_url, userImage } from '../urls';
import { userId } from '../utils/userData';

const ConvHeader = ({conv}) => {

    const {loading, load, data: conver} = useFetch();

    useEffect(() => {
         load(conv_url(conv), 'GET');
    }, [conv])

    return (<>
        {!loading && conver.users &&
            conver.users.map(user => {
                if (user.id !== userId) {
                    return (
                        <div key={user.id} className="row d-flex justify-content-center align-items-center">
                            <div className="col-6 text-left">
                                <img src={userImage(user.picture)} width="50" height="50" 
                                alt={user.name} className="text-left rounded-circle" 
                                style={{'position': 'relative'}} /> 
                                <i className="fas fa-circle text-success" id="is-online"></i>
                            </div>
                            <div className="col-6 text-right">
                                <span className="text-success"> {user.name} </span> <br />
                                <small className="text-muted text-italic">
                                    { moment(new Date(conver.updatedAt)).from() }
                                </small>
                            </div>
                        </div>
                    )
            }
        })
    }</>);
}

export default ConvHeader;