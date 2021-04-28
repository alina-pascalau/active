import React, {Component} from 'react';
import axios from 'axios';
import {Route, Switch,Redirect, Link, withRouter} from 'react-router-dom';

    
class NewProduct extends Component {
     constructor(props) {
        super(props);
        this.state = { 
            categories: [], 
            loading: true,
            name: '',
            description: '',
            price: 0,
            category: null,
        };
        
        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
         
    }
    
    componentDidMount() {
        this.getCategories();
    }
    
    getCategories() {
       axios.get(`/api/categories`).then(categories => {
           this.setState({ categories: categories.data, loading: false})
       })
    }
    handleSubmit(event) {
        console.log('Submit ' + this.state);

        fetch('/api/product', {
                method: 'POST',
                body: JSON.stringify(this.state)
            }).then((result) => {
                console.log(result);
                this.props.history.push('/Categories');
            });
        event.preventDefault();
        
    }
    handleChange(event) {
        const target = event.target;
        const value = target.value;
        const name = target.name;
        this.setState({
            [name]: value
          });
        
      }
      
     render() {
        
        return (
        <div>
            <section className="row-section">
                <div className="container">
                    <div className="row">
                        <h2 className="text-center"><span>Create new product</span></h2>
                    </div>
                    <div className={'row'}>
                    <div className="col-md-10 offset-md-1 row-block">
                        <form onSubmit={this.handleSubmit}>
                          <div className="form-group">
                              <label>Name:</label>
                              <input className="form-control" name="name" type="text" required  onChange={this.handleChange} />
                          </div>
                          <div className="form-group">
                              <label>Description:</label>
                              <textarea className="form-control" name="description" required  onChange={this.handleChange} />
                          </div>
                          <div className="form-group">
                              <label>Price:</label>
                              <input type="text" value={this.state.price} className="form-control" name="price" required  onChange={this.handleChange} />
                          </div>
                          <div className="form-group">
                            <label>Category: </label>
                              <select className="form-control" name="category" onChange={this.handleChange} required>
                                <option value="">Select category</option>
                                  { this.state.categories.map(category => {
                                  return (
                                     <option key={category.id} value={category.id}>{category.name}</option>
                                     )}
                                 )}
                              </select>

                          </div>
                          <button type="submit" className="btn btn-primary">Submit</button>
                        </form>
                    </div>    
                    </div>
                </div>
            </section>
        </div>            
        );
      }
}
export default NewProduct;