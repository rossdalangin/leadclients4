# GrowthPress: System Technical Interaction Map

This map details how the core PHP classes and industry modules interact to form the unified Business OS.

## 1. Data Flow: Lead Capture to CRM
1. **Frontend**: `[gp_lead_form]` shortcode (rendered via `GrowthPress_CRM`) captures user input.
2. **Security**: AJAX request verified with nonces. `GrowthPress_AI::is_spam` filter applied.
3. **Storage**: `wp_insert_post` creates a `gp_lead`.
4. **Trigger**: `do_action('gp_lead_captured', $lead_id)` is fired.
5. **Enrichment**:
   - `GrowthPress_Locations`: Routes lead based on ZIP.
   - `GrowthPress_AI`: Async call to GPT-4 for sentiment and intent scoring.
   - `GrowthPress_Medical/Law`: Specialized niche triage logic is applied if active.

## 2. Page & Asset Synchronization
1. **Trigger**: Admin clicks 'Regenerate Core Assets' (Settings or Dashboard).
2. **Logic**: `GrowthPress_Dashboard::handle_page_regeneration` is called.
3. **Execution**:
   - `generate_niche_pages()`: Pulls Customizer data and block-style copy.
   - `run_niche_sample_data()`: Each active industry module (e.g. `GrowthPress_Solar`) injects niche-specific demo records.
   - `GrowthPress_Reputation`: Injects demo reviews for social proof.

## 3. The Automation Cron Loop
1. **Scheduler**: `gp_cron_followup` runs hourly.
2. **Scout**: `GrowthPress_CRM::run_reactivation_scout` identifies cold leads.
3. **Action**: `GrowthPress_AI` generates a re-engagement offer, stored in lead metadata for team review.

## 4. UI Rendering Engine
- **Admin**: `admin/views/dashboard.php` pulls data from all operational classes (`CRM`, `Booking`, `Proposals`, `Activity`).
- **Frontend**: `style.css` provides the global glassmorphism and animation framework used by all shortcode outputs.
