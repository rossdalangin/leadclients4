<?php
/**
 * GrowthPress AI Core Class - Omni-Intelligence v6.3
 * Supports OpenAI, Anthropic (Claude), Google (Gemini), Perplexity, and Ollama.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_AI {

    /**
     * Strategic Intelligence Node
     *
     * The 'Brain' of the Business OS. This module routes all strategic inquiries
     * through a multi-node AI cluster (OpenAI, Claude, Gemini, etc.) to
     * maintain absolute operational continuity.
     */
    private static $instance = null;
    private $provider;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->provider = get_option('growthpress_ai_provider', 'openai');
        add_action('wp_ajax_gp_submit_ai_feedback', array($this, 'handle_ai_feedback'));
    }

    public function handle_ai_feedback() {
        check_ajax_referer('gp_admin_nonce', 'gp_nonce');
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error();

        $post_id = intval($_POST['post_id']);
        $meta_key = sanitize_text_field($_POST['meta_key']);
        $feedback = sanitize_text_field($_POST['feedback']); // 'positive' or 'negative'

        $log = get_option('gp_ai_feedback_log', array());
        $log[] = array(
            'post_id' => $post_id,
            'key' => $meta_key,
            'feedback' => $feedback,
            'time' => current_time('mysql')
        );
        update_option('gp_ai_feedback_log', $log);

        GrowthPress_Activity::log("Neural Feedback ingested for node #$post_id. Engine recalibrating...");
        wp_send_json_success("Feedback ingested.");
    }

    public function call_ai( $prompt, $context = '' ) {
        switch($this->provider) {
            case 'claude': return $this->call_anthropic($prompt, $context);
            case 'gemini': return $this->call_gemini($prompt, $context);
            case 'perplexity': return $this->call_perplexity($prompt, $context);
            case 'ollama': return $this->call_ollama($prompt, $context);
            default: return $this->call_openai($prompt, $context);
        }
    }

    private function call_openai($prompt, $context) {
        $key = get_option('growthpress_openai_api_key');
        if(!$key) return new WP_Error('missing_key', 'OpenAI key missing.');

        $res = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
            'headers' => array('Authorization' => 'Bearer '.$key, 'Content-Type' => 'application/json'),
            'body' => json_encode(array(
                'model' => 'gpt-4-turbo',
                'messages' => array(array('role'=>'system','content'=>$context), array('role'=>'user','content'=>$prompt))
            )),
            'timeout' => 30
        ));
        if(is_wp_error($res)) return $res;
        $body = json_decode(wp_remote_retrieve_body($res), true);
        return $body['choices'][0]['message']['content'] ?? new WP_Error('empty','Empty OpenAI response.');
    }

    private function call_anthropic($prompt, $context) {
        $key = get_option('growthpress_claude_api_key');
        if(!$key) return new WP_Error('missing_key', 'Claude key missing.');

        $res = wp_remote_post('https://api.anthropic.com/v1/messages', array(
            'headers' => array('x-api-key' => $key, 'anthropic-version' => '2023-06-01', 'Content-Type' => 'application/json'),
            'body' => json_encode(array(
                'model' => 'claude-3-opus-20240229',
                'system' => $context,
                'messages' => array(array('role'=>'user','content'=>$prompt)),
                'max_tokens' => 4000
            )),
            'timeout' => 30
        ));
        if(is_wp_error($res)) return $res;
        $body = json_decode(wp_remote_retrieve_body($res), true);
        return $body['content'][0]['text'] ?? new WP_Error('empty','Empty Claude response.');
    }

    private function call_gemini($prompt, $context) {
        $key = get_option('growthpress_gemini_api_key');
        if(!$key) return new WP_Error('missing_key', 'Gemini key missing.');

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" . $key;
        $res = wp_remote_post($url, array(
            'headers' => array('Content-Type' => 'application/json'),
            'body' => json_encode(array(
                'contents' => array(array('parts'=>array(array('text'=>$context . "\n\n" . $prompt))))
            )),
            'timeout' => 30
        ));
        if(is_wp_error($res)) return $res;
        $body = json_decode(wp_remote_retrieve_body($res), true);
        return $body['candidates'][0]['content']['parts'][0]['text'] ?? new WP_Error('empty','Empty Gemini response.');
    }

    private function call_perplexity($prompt, $context) {
        $key = get_option('growthpress_perplexity_api_key');
        if(!$key) return new WP_Error('missing_key', 'Perplexity key missing.');

        $res = wp_remote_post('https://api.perplexity.ai/chat/completions', array(
            'headers' => array('Authorization' => 'Bearer '.$key, 'Content-Type' => 'application/json'),
            'body' => json_encode(array(
                'model' => 'pplx-70b-chat',
                'messages' => array(array('role'=>'system','content'=>$context), array('role'=>'user','content'=>$prompt))
            )),
            'timeout' => 30
        ));
        if(is_wp_error($res)) return $res;
        $body = json_decode(wp_remote_retrieve_body($res), true);
        return $body['choices'][0]['message']['content'] ?? new WP_Error('empty','Empty Perplexity response.');
    }

    private function call_ollama($prompt, $context) {
        $host = get_option('growthpress_ollama_host', 'http://localhost:11434');
        $model = get_option('growthpress_ollama_model', 'llama3');

        $res = wp_remote_post($host . '/api/generate', array(
            'body' => json_encode(array(
                'model' => $model,
                'prompt' => $context . "\n\n" . $prompt,
                'stream' => false
            )),
            'timeout' => 60
        ));
        if(is_wp_error($res)) return $res;
        $body = json_decode(wp_remote_retrieve_body($res), true);
        return $body['response'] ?? new WP_Error('empty','Empty Ollama response.');
    }

    public function generate_growth_roadmap( $niche ) {
        return $this->call_ai( "Generate a 12-month business growth and AI automation roadmap for a $niche business.", "You are a growth strategist." );
    }

    public function predict_deal_probability( $lead_id ) {
        $lead = get_post($lead_id);
        $res = $this->call_ai("Predict probability of closing (0-100) for inquiry: \"{$lead->post_content}\". Return ONLY number.", "Sales Predictor");
        return is_numeric(trim($res)) ? intval(trim($res)) : 75;
    }

    public function analyze_sentiment( $msg ) {
        return $this->call_ai("Analyze sentiment/urgency of \"$msg\". Return JSON: sentiment, urgency (1-10).", "Lead Assistant");
    }

    public function generate_blog_post($t, $n) { return $this->call_ai("Write a 1000-word SEO blog post about \"$t\" for a $n.", "Content Specialist"); }
    public function generate_ad_copy($s, $n) { return $this->call_ai("Create 3 high-converting ads for \"$s\" in $n.", "Ad Copywriter"); }
    public function generate_email_campaign($topic, $niche) { return $this->call_ai("Generate 5-day email sequence for \"$topic\" in $niche.", "Email Marketer"); }
    public function generate_market_insights($topic, $niche) { return $this->call_ai("Analyze market for \"$topic\" in $niche. Identify gaps.", "Market Strategist"); }

    public function is_spam($m, $n, $e) {
        $res = $this->call_ai("Is this spam: Content: \"$m\", Name: \"$n\", Email: \"$e\"? Return ONLY 'SPAM' or 'LEGIT'.", "Security Filter");
        return (trim($res) === 'SPAM');
    }

    public function generate_proposal( $data ) {
        $niche = $data['niche'] ?? 'general';
        $inquiry = $data['inquiry'] ?? 'No inquiry provided';
        $sentiment = $data['sentiment'] ?? 'Neutral';
        $prob = $data['prob'] ?? 50;

        $prompt = "Generate a professional business growth proposal for a client in the $niche industry.\n";
        $prompt .= "Client Inquiry: \"$inquiry\"\n";
        $prompt .= "Sentiment Analysis: $sentiment\n";
        $prompt .= "Closing Probability: $prob%\n";
        $prompt .= "Structure: Executive Summary, Strategic Solution, Implementation Timeline, and ROI Forecast.";

        return $this->call_ai( $prompt, "Proposal Architect Specialist" );
    }

    public function generate_missed_call_reply( $caller_number ) {
        return $this->call_ai( "Generate a polite, professional SMS response for a missed business call from {$caller_number}.", "Customer Support AI" );
    }

    public function generate_behavioral_nudge( $lead_id ) {
        $lead = get_post($lead_id);
        return $this->call_ai( "Based on this inquiry: \"{$lead->post_content}\", provide a 1-sentence psychological nudge for the sales rep to use during the first 30 seconds of the call. Focus on loss aversion or reciprocity.", "Psychology Expert" );
    }
}
GrowthPress_AI::get_instance();
