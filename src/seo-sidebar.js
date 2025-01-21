import { useEffect, useState } from '@wordpress/element';
import { select, subscribe } from '@wordpress/data';
import { Button, Spinner } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import './index.css';

const SEOAnalyzer = () => {
    const [content, setContent] = useState('');
    const [metaKeywords, setMetaKeywords] = useState('');
    const [suggestions, setSuggestions] = useState(null);
    const [loading, setLoading] = useState(false);

    // Track post content changes
    useEffect(() => {
        const unsubscribe = subscribe(() => {
            const newContent = select('core/editor').getEditedPostContent();
            const newMeta = select('core/editor').getEditedPostAttribute('meta')?.seo_keywords || '';

            setContent(newContent);
            setMetaKeywords(newMeta);
        });

        return () => unsubscribe();
    }, []);

    // Fetch suggestions from REST API
    const fetchSuggestions = async () => {
        setLoading(true);
        try {
            const response = await fetch('/checker/wp-json/seo-suggestions/v1/analyze', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ content, meta_keywords: metaKeywords }),
            });

            if (response.ok) {
                const data = await response.json();
                setSuggestions(data);
            } else {
                console.error('Failed to fetch suggestions');
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className='content-ai-optimiser'>
            <h2>{__('SEO Suggestions', 'content-ai-optimizer')}</h2>
            <Button isPrimary onClick={fetchSuggestions}>
                {__('Analyze Content', 'content-ai-optimizer')}
            </Button>
            {loading && <Spinner />}
            {suggestions && (
                 <div>
                 <h3>{__('Analysis Results', 'content-ai-optimizer')}</h3>
                 <div className="seo-analysis-results">
                     <div className="result-item">
                         <h4>{__('Keyword Density', 'content-ai-optimizer')}</h4>
                         <p>{suggestions.keyword_density}</p>
                     </div>
                     <div className="result-item">
                         <h4>{__('Heading Optimization', 'content-ai-optimizer')}</h4>
                         <p>{suggestions.heading_optimization}</p>
                     </div>
                     <div className="result-item">
                         <h4>{__('Alt Tags', 'content-ai-optimizer')}</h4>
                         <p>{suggestions.alt_tags}</p>
                     </div>
                 </div>
             </div>
            )}
        </div>
    );
};

export default SEOAnalyzer;
