import React from 'react';
import Convs from '../components/convs';
import Users from '../components/users';

const Home = () => {

    return (
        <section className="container">
            <div className="row d-flex justify-content-center">
                <div className="convs col-lg-6"><Convs /></div>
                <div className="users col-lg-6"><Users /></div>
            </div>
        </section>
    );
}

export default Home;