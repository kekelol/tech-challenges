import React from 'react';

import './MainContent.css';

import SideBar from './SideBar/SideBar';
import PageContent from './PageContent/PageContent';

const mainContent = () => (
    <div className='MainContent'>
        <SideBar />
        <PageContent agencyCode='XX2' />
    </div>
)

export default mainContent;