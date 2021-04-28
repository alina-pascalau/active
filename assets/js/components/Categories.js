    
import React, {Component} from 'react';
import axios from 'axios';
import {Route, Switch,Redirect, Link, withRouter} from 'react-router-dom';
import Product from './Product';

    
class Categories extends Component {
    constructor() {
        super();
        this.state = { categories: [], loading: true};
    }
    
    componentDidMount() {
        this.getCategories();
    }
    
    getCategories() {
       axios.get(`/api/categories`).then(categories => {
           this.setState({ categories: categories.data, loading: false})
       })
    }
    
    deleteProduct(productId){
        const requestOptions = {
          method: 'DELETE'
        };
        fetch("/api/product/" + productId, requestOptions).then((response) => {
          return response.json();
        }).then((result) => {
            console.log(result);
            this.getCategories();
          //window.location.reload();
        });
      }
    
    
    render() {
        const loading = this.state.loading;
        return(
            <div>
                <section className="row-section">
                    <div className="container">
                        <div className="row">
                            <h2 className="text-center"><span>List of categories</span></h2>
                        </div>
                        {loading ? (
                            <div className={'row text-center'}>
                                <span className="fa fa-spin fa-spinner fa-4x"></span>
                            </div>
                        ) : (
                            <div className={'row'}>
                                { this.state.categories.map(category =>
      
                                    <div className="col-md-10 offset-md-1 row-block" key={category.id}>
                                        <ul id="sortable">
                                            <li>
                                                <div className="media">
                                                 
                                                    <div className="media-body">
                                                        <h3>{category.name}</h3>
                                                        <p>{category.description}</p>
                                                        <h5>Products</h5>
                                                        <ul className="list-group">
                                                         {category.products.map(product => {
                                                             return (
                                                                <li className="list-group-item d-flex justify-content-between align-items-center" key={product.id}>
                                                                <Link to={{pathname: `Product/`, query: { id: product.id }}}>{product.name}</Link>
                                                                    <button type="button" onClick={() => { this.deleteProduct(product.id) }} className="btn btn-danger">Delete</button>
                                                                </li>
                                                                    )
                                                          })}
                                                          </ul>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                )}
                            </div>
                        )}
                    </div>
                </section>
            </div>
        )
    }
}
export default Categories;