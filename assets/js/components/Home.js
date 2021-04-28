import React, {Component} from 'react';
import {Route, Switch,Redirect, Link, withRouter} from 'react-router-dom';
import Categories from './Categories';
import NewProduct from './NewProduct';
import Product from './Product';

    
class Home extends Component {
    
    render() {
        return (
           <div>
               <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                   <Link className={"navbar-brand"} to={"/"}> Symfony React Project </Link>
                   <div className="collapse navbar-collapse" id="navbarText">
                       <ul className="navbar-nav mr-auto">
                           <li className="nav-item">
                               <Link className={"nav-link"} to={"/Categories"}> Categories </Link>
                           </li>
    
                           <li className="nav-item">
                               <Link className={"nav-link"} to={"/Product"}> Product </Link>
                           </li>
                           
                           <li className="nav-item">
                               <Link className={"nav-link"} to={"/NewProduct"}> New Product </Link>
                           </li>
                       </ul>
                   </div>
               </nav>
               <Switch>
                   <Redirect exact from="/" to="/categories" />
                   <Route path="/product" component={Product} />
                   <Route path="/categories" component={Categories} />
                   <Route path="/newproduct" component={NewProduct} />
                   
                   
                   
               </Switch>
           </div>
        )
    }
}
    
export default Home;