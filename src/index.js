import React from 'react';
import App from './App';
import { registerPlugin } from '@wordpress/plugins';
import { PluginSidebar } from '@wordpress/edit-post';
import { __ } from '@wordpress/i18n';
import SEOAnalyzer from './seo-sidebar';

registerPlugin('content-ai-optimiser',{
  render:() =>  (<PluginSidebar name='content-ai-optimiser' title={__('Content AI Optimizer', 'content-ai-optimizer')}>
    <SEOAnalyzer />
  </PluginSidebar>
),
})


const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
