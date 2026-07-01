# GrowthPress Database Schema & Metadata Mapping v6.3

For enterprise-level data integration, this document provides the granular mapping of all 14 Custom Post Types, Taxonomies, and Meta Keys within the Elite ecosystem.

## 1. Custom Post Types (CPTs)
- `gp_lead`: The core prospect record with AI sentiment tracking.
- `gp_task`: Internal operational tasks, often AI-generated.
- `gp_appointment`: Scheduling records for multi-staff booking.
- `gp_proposal`: Strategic service quotes and binding contracts.
- `gp_transaction`: Financial ledger records (Revenue & OpEx).
- `gp_funnel`: A/B testing nodes for conversion tracking.
- `gp_location`: Physical service hubs for ZIP-based routing.
- `gp_kb`: Knowledge Base/Authority articles.
- `gp_project`: Case studies and ROI-verified success stories.
- `gp_service`: Core business service methodology lines.
- `gp_property`: Luxury portfolio inventory assets (Real Estate).
- `gp_review`: Automated reputation and review management.
- `gp_treatment`: Specialized clinical protocols (Medical/Dental).
- `gp_staff`: Specialist team nodes with performance radars.

## 2. Taxonomies
- `gp_lead_stage`: pipeline status (new, qualified, booked, closed).
- `gp_lead_tag`: strategic segmentation (residential, commercial, enterprise, high-roi).

## 3. Metadata Reference (Standard Prefix: `_gp_` or `_lead_`)
| Meta Key | Context | Description |
| :--- | :--- | :--- |
| `_gp_ai_probability` | Lead | Predicted close percentage (0-100). |
| `_gp_ai_sentiment_json`| Lead | Granular AI sentiment and intent analysis data. |
| `_gp_is_sample` | All CPTs | Boolean flag identifying system-generated demo data. |
| `_gp_growth_roi` | Project | String representing measurable growth (e.g. +320%). |
| `_gp_pipeline_value` | Project/Lead | Estimated total value added to the ecosystem. |
| `_gp_roadmap_milestones`| Lead | Array tracking progress through the AI Strategic Roadmap. |
| `_appointment_date` | Appointment | Scheduled date/time string (YYYY-MM-DD HH:MM). |
| `_meeting_link` | Appointment | Auto-generated secure Zoom/Telemedicine URL. |
| `_proposal_recipient` | Proposal | Secure email for digital agreement dispatch. |
| `_gp_proposal_status` | Proposal | Lifecycle stage (Sent, Accepted, Archived). |
| `_staff_performance_json`| Staff | Performance metrics for the specialist radar chart. |
| `_treatment_duration` | Treatment | Clinical timeframe for specialized protocols. |

## 4. Global Operational Options
- `growthpress_niche`: The active industry persona.
- `growthpress_ai_provider`: Active intelligence node (OpenAI, Claude, etc).
- `growthpress_api_token`: Secure Bearer token for REST API.
- `gp_activity_logs`: Serialized array of system events.
