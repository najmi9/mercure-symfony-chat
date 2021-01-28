import React from 'react';
import Home from './pages/home';
import {
  BrowserRouter as Router,
  Switch,
  Route,
} from "react-router-dom";
import Conv from './pages/conv';

const App = () => {

    return(
        <Router>
            <Switch>
                <Route path="/" component={Home } exact/>
                <Route path="/convs/:id" component={Conv} exact/>
            </Switch>
        </Router>
    );
}

export default App;