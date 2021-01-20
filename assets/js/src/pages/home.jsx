import React, { useEffect, useState } from 'react';
import Convs from './convs';
import Users from './users';

const Home = () => {
   
    return(
        <section>
            <h1> Hello Mercure! </h1>
            <div className="row">
                <div className="users col-lg-4 col-6"><Users /> </div>
                <div className="convs col-lg-4 col-6"><Convs /></div>
                <div className="msgs col-lg-4 col-6"></div>
            </div>
        </section>
    );
}

export default Home;