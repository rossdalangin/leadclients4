# GrowthPress AI Automations

## Automation Flows
1. **Lead Qualification**:
   - New Form Submission -> AI Sentiment Analysis -> Automated Lead Scoring -> Notification to Team.
2. **Missed Appointment Recovery**:
   - Appointment Status: 'No Show' -> Trigger SMS sequence -> Provide Re-booking Link.
3. **Emergency Detection**:
   - Message Content contains 'emergency' or 'urgent' -> AI boosts score to 100 -> Immediate Lead Routing.
4. **Booking Intent Detection**:
   - AI Chat Bubble detects "book" or "schedule" intent -> Dynamically suggests Booking Link.
5. **Review Generation**:
   - Appointment Status: 'Completed' -> Trigger Automated Review Request email/SMS.
6. **Proposal to Payment**:
   - Client Accepts Proposal in Portal -> Automatically generates Deposit Invoice in Payments engine.

## Key Shortcodes
- `[gp_lead_form]`: Standard lead capture.
- `[gp_quiz_lead_form]`: Multi-step conversational quiz capture.
- `[gp_booking_form]`: Appointment scheduling with staff selection.
- `[gp_ai_faq]`: Conversational AI assistant with Chat Bubble support.
- `[gp_review_feed]`: Reputation social proof.
- `[gp_client_portal]`: Customer dashboard with project tracking.
- `[gp_solar_calculator]`: Interactive ROI tool with AI Consultant.
- `[gp_contractor_estimator]`: Instant project pricing.
- `[gp_before_after]`: Visual proof component.
- `[gp_urgency_banner]`: Dynamic niche-specific urgency alerts.
