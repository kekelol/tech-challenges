import React, { Component } from 'react';

import './App.css';

import Header from './Header/Header';
import MainContent from './MainContent/MainContent';

class App extends Component {
  render() {
    return (
      <div className="App">
        <Header />
        <MainContent />
      </div>
    );
  }
}

export default App;
