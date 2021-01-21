import React, { useState } from 'react';
import AuthContext from './contexts/AuthContext';
import Home from './pages/home';

const App = () => {

    const [user, setUser] = useState();

    return(
        <AuthContext.Provider value={{
            user,
            setUser
        }}>
            <Home />
        </AuthContext.Provider>
    );
}

export default App;