import React from 'react';

import DataVisualizationItem from './DataVisualizationItem/DataVisualizationItem';

import './DataVisualizationWidget.css';

class DataVisualizationWidget extends React.Component {

    widgetContent = 'This type of widget is not implemented yet';
    widgetClass = '';

    render(props) {
        
        if (this.props.questionData.type == 'qcm') {
            this.widgetClass = 'DataVisualizationWidget QcmWidget';
            this.widgetContent = 'todo';
            let questions = [];
            // TODO : Replace with Highcharts (one column per product)
            for (let i = 0; i < this.props.questionData.questions.length; i++) {
                questions.push(<DataVisualizationItem name={this.props.questionData.questions[i]} value={this.props.questionData.answers[i]} />);
            }
            return (
                <div className={this.widgetClass} id={this.props.key}>
                    <h3>{this.props.questionData.label}</h3>
                    <p><ul>{questions}</ul></p>
                </div>
            )
        }
        else if (this.props.questionData.type == 'numeric') {
            this.widgetClass = 'DataVisualizationWidget NumericWidget';
            this.widgetContent = this.props.questionData.average;
            return (
                <div className={this.widgetClass} id={this.props.key}>
                    <h3>{this.props.questionData.label}</h3>
                    <p>{this.widgetContent}</p>
                </div>
            )
        }
        else {
            return (
                <div className={this.widgetClass} id={this.props.key}>
                    <h3>{this.props.questionData.label}</h3>
                    <p>{this.widgetContent}</p>
                </div>
            )
        }
    }
}

export default DataVisualizationWidget;