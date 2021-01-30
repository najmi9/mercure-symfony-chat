import React from 'react';
import Home from './pages/home';
import {
  BrowserRouter as Router,
  Switch,
  Route,
} from "react-router-dom";
import Conv from './pages/conv';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import SpinningDots from '@grafikart/spinning-dots-element'

customElements.define('spinning-dots', SpinningDots);

const App = () => {

    return(
        <Router>
            <Switch>
                <Route path="/" component={Home } exact/>
                <Route path="/convs/:id" component={Conv} exact/>
            </Switch>
            <ToastContainer position='top-right'/>
        </Router>
    );
}

export default App;