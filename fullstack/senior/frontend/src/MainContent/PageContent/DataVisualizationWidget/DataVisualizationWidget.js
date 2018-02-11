import React from 'react';

import './DataVisualizationWidget.css';

class DataVisualizationWidget extends React.Component {

    label = '';
    type = '';

    componentDidMount() {
        
    }

    render(props) {
        return (
            <div className='DataVisualizationWidget'>
                <h3>{this.props.questionData.label}</h3>
            </div>
        )
    }
}

export default DataVisualizationWidget;