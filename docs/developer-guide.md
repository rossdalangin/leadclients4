# GrowthPress Developer Guide

## System Hooks
- `gp_lead_captured`: Fires after AI spam filtering and lead creation.
- `gp_appointment_created`: Fires after a booking is confirmed.
- `gp_proposal_accepted`: Fires when a client clicks 'Accept' in the portal.
- `gp_niche_lead_analysis`: Fires during the async triage loop; use this to add niche-specific data to a lead.
- `gp_emergency_booking`: Fires when a high-urgency lead is detected by the AI.

## Core Filters
- `gp_ai_system_prompt`: Allows developers to modify the global AI persona.
- `gp_lead_score_multiplier`: Adjust lead scoring weights based on custom criteria.
- `gp_dashboard_stats`: Add custom cards to the executive dashboard.

## AI Engine (`GrowthPress_AI`)
Extensible methods for:
- `predict_deal_probability( $lead_id )`: Uses lead score and behavior history.
- `is_spam( $msg, $name, $email )`: Specialized security triage.

## Database Schema
GrowthPress utilizes standard WordPress tables with metadata mapping for maximum compatibility and performance.
- Meta prefix: `_gp_`
- Custom Taxonomies: `gp_lead_stage`, `gp_lead_tag`.
