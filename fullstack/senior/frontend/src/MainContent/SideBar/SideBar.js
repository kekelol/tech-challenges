import React from 'react';

import './SideBar.css';

const sideBar = () => (
    <div className='SideBar'>
        <label for="agency-search">Agency search</label>
        <input type="text" name="agency-search" placeholder="XX2 (search not implemented yet)"></input>
    </div>
)

export default sideBar;