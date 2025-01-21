<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class SEO_Suggestions {

    public function __construct() {

        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes() {
        error_log( 'Registering REST route for SEO Suggestions' );
        register_rest_route(
            'seo-suggestions/v1',
            '/analyze',
            [
                'methods'  => 'POST',
                'callback' => [ __CLASS__, 'analyze_content' ],
                'permission_callback' => '__return_true',
            ]
        );

    }

    public static function analyze_content(WP_REST_REQUEST $request) {

         // Get content and meta fields from the request
         $content = $request->get_param('content');
         $meta_keywords = $request->get_param('meta_keywords');
 
         // Perform SEO analysis
         $results = [
             'keyword_density' => self::calculate_keyword_density($content, $meta_keywords),
             'heading_optimization' => self::check_headings($content),
             'alt_tags' => self::check_alt_tags($content),
         ];
 
         return rest_ensure_response($results);

    }

    private static function calculate_keyword_density($content, $keyword) {
        if (empty($keyword)) {
            return 'No keyword provided.';
        }

        $word_count = str_word_count(strip_tags($content));
        $keyword_count = substr_count(strtolower(strip_tags($content)), strtolower($keyword));

        $density = ($word_count > 0) ? ($keyword_count / $word_count) * 100 : 0;

        return [
            'keyword' => $keyword,
            'density' => round($density, 2) . '%',
            'suggestion' => $density > 2 && $density < 5
                ? 'Optimal keyword density.'
                : 'Adjust keyword usage for better SEO.',
        ];
    }

    private static function check_headings($content) {
        preg_match_all('/<h([1-6])>(.*?)<\/h\1>/', $content, $matches);

        if (empty($matches[0])) {
            return 'No headings found. Add H1-H3 tags for better SEO.';
        }

        return 'Headings are present and analyzed.';
    }

    private static function check_alt_tags($content) {
        preg_match_all('/<img[^>]+alt="([^"]*)"/', $content, $matches);

        if (empty($matches[1])) {
            return 'Images without alt tags found. Add descriptive alt attributes.';
        }

        return 'All images have alt attributes.';
    }

}
// Instantiate the class.
new SEO_Suggestions();