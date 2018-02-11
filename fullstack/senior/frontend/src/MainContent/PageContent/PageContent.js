import React from 'react';

import './PageContent.css';

import PageTitle from './PageTitle/PageTitle';
import DataVisualizationWidget from './DataVisualizationWidget/DataVisualizationWidget';

class PageContent extends React.Component {
    
    endpoint = 'http://localhost:8080/XX2'; // Endpoint URL for fetching data into Page Content

    constructor(props) {
        super(props);
        this.state = {
            error: null,
            isLoaded: false,
            xhrResult: []
        };
    }

    componentDidMount() {
        fetch(this.endpoint)
        .then(res => res.json())
        .then(
            (result) => {
                this.setState({
                    isLoaded: true,
                    xhrResult: result
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
        }
        else if (!isLoaded) {
          return <div>Loading... Please wait</div>;
        }
        else {
            console.log(this.state.xhrResult);
            let widgets = [];
            // Add as many widget on the page as there are questions
            for (let i = 0; i < this.state.xhrResult.questions.length; i++) {
                // note: we add a key prop here to allow react to uniquely identify each
                // element in this array. see: https://reactjs.org/docs/lists-and-keys.html
                widgets.push(<DataVisualizationWidget key={i} questionData={this.state.xhrResult.questions[i]} />);
            }
            return (
                <div className='PageContent'>
                    <PageTitle agencyName={this.state.xhrResult.agency.name} agencyCode={this.state.xhrResult.agency.code} />
                    {widgets}
                </div>
            );
        }
    }
}

export default PageContent;