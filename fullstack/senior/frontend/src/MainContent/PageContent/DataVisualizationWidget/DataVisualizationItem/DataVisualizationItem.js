import React from 'react';

const dataVisualizationItem = (props) => (
    <li className='DataVisualizationItem'>
        {props.name} : {props.value}
    </li>
)

export default dataVisualizationItem;