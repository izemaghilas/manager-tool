/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import reactDom from 'react-dom';
import React from 'react';

function App(){
    return(
        <div style={{ backgroundColor: 'green' }}>
            <h1>App component</h1>
        </div>
    );
}

reactDom.render(
    <React.StrictMode>
        <App/>
    </React.StrictMode>,
    document.getElementById("root")
);
