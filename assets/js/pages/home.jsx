import React from 'react';
import Convs from '../partials/convs';
import Users from '../partials/users';

const Home = () => {

    return (
        <section>
            <h1> Hello Mercure! </h1>
            <div className="row d-flex justify-content-center">
                <div className="convs col-lg-4"><Convs /></div>
                <div className="msgs col-lg-4 "></div>
                <div className="users col-lg-4">
                    <h3 className="text-center"> Users:  </h3>
                    <Users />
                </div>
            </div>
        </section>
    );
}

export default Home;