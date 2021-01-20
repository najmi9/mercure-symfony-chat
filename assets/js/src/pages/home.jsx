import React, { useEffect, useState } from 'react';
import Convs from './convs';
import Msgs from './Msgs';
import Users from './users';

const Home = () => {
   
    return(
        <section>
            <h1> Hello Mercure! </h1>
            <div className="row">
                <div className="users col-lg-4"><Users /> </div>
                <div className="convs col-lg-4"><Convs /></div>
                <div className="msgs col-lg-4"></div>
            </div>
        </section>
    );
}

export default Home;