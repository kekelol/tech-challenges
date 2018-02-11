import React from 'react';

import './PageTitle.css';

const pageTitle = (props) => (
    <div className='PageTitle'>
        <h1>{props.agencyName}</h1>
        <h2>Agency code: {props.agencyCode}</h2>
    </div>
)

export default pageTitle;