import React from 'react';

import './PageContent.css';

import PageTitle from './PageTitle/PageTitle';
import DataVisualizationWidget from './DataVisualizationWidget/DataVisualizationWidget';

class PageContent extends React.Component {
    
    state = {
        agency: {
          name: "Chartres",
          code: "XX2" 
        },
        questions: [
            {
                type: "qcm", 
                label: "What best sellers are available in your store?", 
                answers: [{"Product 1": 4},{"Product 2": 4},{"Product 3": 6},{"Product 4": 3},{"Product 5": 6},{"Product 6": 3}]
            },
            {
                type: "numeric",
                label: "Number of products?",
                sum: 28400,
                count: 6,
                average: 4733
            }
        ]
    }

    constructor(props) {
        super(props);
        this.state = {
            error: null,
            isLoaded: false,
            items: []
        };
    }

    componentDidMount() {
        fetch("http://localhost:8080/XX2")
        .then(res => res.json())
        .then(
            (result) => {
                this.setState({
                    isLoaded: true,
                    items: result.items
                });
            },
            (error) => {
                this.setState({
                    isLoaded: true,
                    error
                });
            }
        )
    }
    
    render() {
        const { error, isLoaded, items } = this.state;
        if (error) {
          return <div>Error: {error.message}</div>;
        } else if (!isLoaded) {
          return <div>Loading... Please wait</div>;
        } else {
            var widgets = [];
            // Add as many widget on the page as there are questions
            for (var i = 0; i < this.state.questions.length; i++) {
                // note: we add a key prop here to allow react to uniquely identify each
                // element in this array. see: https://reactjs.org/docs/lists-and-keys.html
                widgets.push(<DataVisualizationWidget key={i} questionData={this.state.questions[i]} />);
            }
          return (
            <div className='PageContent'>
                <PageTitle agencyName={this.state.agency.name} agencyCode={this.state.agency.code} />
                {widgets}
            </div>
            /*<ul>
              {items.map(item => (
                <li key={item.name}>
                  {item.name} {item.price}
                </li>
              ))}
            </ul>*/
          );
        }
      }
}

export default PageContent;