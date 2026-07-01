# GrowthPress Elite: API & Integration Specification v6.3

The GrowthPress OS is built with a REST-first architecture, allowing seamless integration with Zapier, Make.com, and custom enterprise middleware.

## 🔒 Authentication
All requests must include the `growthpress_api_token` in the Bearer header.
`Authorization: Bearer YOUR_TOKEN`

## 📡 Endpoints

### 1. Lead Injection (`POST /wp-json/growthpress/v1/leads`)
Inject leads from external sources (e.g., Facebook Lead Ads).
**Payload**: `{"name": "...", "email": "..."}`

### 2. Missed Call Automation (`POST /wp-json/growthpress/v1/missed-call`)
Twilio webhook endpoint for automated missed call follow-up.
**Payload**: `{"From": "+1234567890"}`
**Logic**: Triggers the AI to generate a niche-aware SMS response.

### 3. Financial Ledger Sync (`POST /wp-json/growthpress/v1/sync-transactions`)
Synchronize external payments into the GrowthPress ROI Hub.
**Payload**: `{"amount": 1500, "title": "External Invoice #102"}`

### 4. Operational Availability (`GET /wp-json/growthpress/v1/get-availability`)
Fetch currently booked slots in the master calendar for external booking integrations.

---

## 🛠️ Internal Hooks for Developers
*   `gp_lead_captured`: Triggered after successful intake.
*   `gp_appointment_created`: Fires when a strategy session is confirmed.
*   `gp_appointment_completed`: Triggers reputation and review sequences.
*   `gp_niche_lead_analysis`: Fires after the AI completes sentiment and intent scoring.
