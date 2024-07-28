import React from 'react'
import ReactDOM from 'react-dom';
import Frontend from './Frontend'

const element2 = document.getElementById("ce-app")
if (element2 !== 'undefined' && element2 !== null) {
    ReactDOM.render(<Frontend />, document.getElementById("ce-app"))
}
