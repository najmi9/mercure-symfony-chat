import React from 'react';
import Convs from '../partials/convs';
import Users from '../partials/users';

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