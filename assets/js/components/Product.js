    
import React, {Component} from 'react';
import axios from 'axios';
import {Route, Switch,Redirect, Link, withRouter} from 'react-router-dom';
import Categories from './Categories';

    
class Product extends Component {
    constructor(props) {
       
        super(props);
        this.state = { 
            categories: [],
            loading: true, 
            product: [],
        };
        //TODO
        if(this.props.location.query.id){
            this.state.productId = this.props.location.query.id;
        }
    }
    
    componentDidMount() {
        this.getProduct();
        this.getCategories();
    }
    
    getCategories() {
       axios.get(`/api/categories`).then(categories => {
           this.setState({ categories: categories.data, loading: false})
       })
    }
    
    getProduct() {
       axios.get(`/api/product/`+ this.state.productId).then(product => {
           this.setState({ product: product.data, loading: false})
       })}

    render() {
        
        const loading = this.state.loading;
        return(
            <div>
                <section className="row-section">
                    <div className="container">
                        <div className="row">
                            <h2 className="text-center"><span>{this.state.product.name}</span></h2>
                        </div>
                        {loading ? (
                            <div className={'row text-center'}>
                                <span className="fa fa-spin fa-spinner fa-4x"></span>
                            </div>
                        ) : (
                            <div className={'row'}>
                            <div className="col-md-10 offset-md-1 row-block">
                                 <div className="media">
                                    <div className="media-body">
                                        <p>{this.state.product.description}</p>
                                        <p>Price: <span className="badge badge-secondary">{this.state.product.price}</span></p>
                                             
                                    </div>
                                </div>
                            </div>
                            </div>
                        )}
                    </div>
                </section>
            </div>
        )
    }
}
export default Product;