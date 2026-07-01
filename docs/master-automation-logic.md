# GrowthPress Master Automation Logic

This document details the exact technical workflows that power the GrowthPress autonomous business operating system.

## 1. The Intake & Qualification Workflow (Lead Brain)
- **Trigger**: Visitor submits `[gp_quiz_lead_form]`.
- **Logic**:
  1. **Spam Scan**: Content is sent to `GrowthPress_AI::is_spam`. If 'SPAM', lead is discarded and error is logged.
  2. **Ingestion**: Entry stored as `gp_lead` with 'New' stage.
  3. **Sentiment Sync**: Async call to GPT-4 via `gp_async_lead_analysis`.
  4. **Intent Detection**: AI identifies if lead is Residential, Commercial, or Enterprise.
  5. **Urgency Routing**: If AI Score > 9/10, an urgent `gp_task` is created and assigned to the Admin.
  6. **Niche Brief**: AI generates 4 discovery questions based on the specific industry (e.g., Dental vs. Solar).

## 2. The Multi-Staff Booking Logic
- **Trigger**: Client accesses `[gp_booking_form]`.
- **Logic**:
  1. **Availability Scan**: Engine checks `gp_appointment` CPT for existing slots.
  2. **Confirmation**: Upon selection, `gp_appointment` is created and assigned to `_staff_id`.
  3. **Reminder Engine**: Automated hook triggers at 24h and 1h intervals (simulated via cron) to send SMS/Email reminders.
  4. **Waiting List**: If no slots are available, user can join the `_is_waiting_list` queue, flagged in the CRM dashboard.

## 3. The 5-Day Omnichannel Nurture
- **Trigger**: Lead stage remains 'New' or 'Qualified' without a booked appointment.
- **Logic**:
  1. **Day 1**: AI generates 'Reciprocity' email offering the Niche Roadmap.
  2. **Day 2**: Social Proof delivery via `gp_review` CPT highlights.
  3. **Day 3**: Pain-point deep dive based on original inquiry sentiment.
  4. **Day 4**: ROI/Loss Aversion logic anchoring the price.
  5. **Day 5**: Scarcity trigger using the `gp_urgency_banner` availability count.

## 4. The Proposal-to-Cash Lifecycle
- **Trigger**: Admin clicks 'Generate AI Strategic Proposal' in Lead screen.
- **Logic**:
  1. **Drafting**: AI pulls lead sentiment and niche data to write a custom quote.
  2. **Notification**: Client receives portal access link.
  3. **Acceptance**: Client clicks 'Accept' in the portal.
  4. **Post-Acceptance Hook**:
     - `gp_transaction` created in Payments engine.
     - `gp_task` created for "Project Kickoff".
     - Lead stage automatically moved to 'Closed' in Kanban.
