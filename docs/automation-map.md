# GrowthPress Automation Maps

This document outlines the core logic flows within the GrowthPress Business Operating System.

## 1. Lead-to-Booking Flow
```
[Visitor]
   |
   V
[Lead Capture Form / Quiz] ----> [AI Spam Filter] ----> (Block if Spam)
   |
   V
[Lead Stored in CRM] ----> [AI Sentiment Analysis] ----> [Lead Scoring]
   |                             |                          |
   V                             V                          V
[Routing to Location]    [Auto-Response Created]     [Urgent Task Created]
   |                             |
   V                             V
[One-Click Booking] <----------[Follow-up SMS/Email]
```

## 2. Proposal Acceptance Flow
```
[Proposal Generated via AI]
   |
   V
[Client views in Portal]
   |
   V
[Client clicks 'Accept']
   |
   V
[GP_PROPOSAL_ACCEPTED Hook]
   |
   +----> [Create Project Kickoff Task]
   +----> [Generate Deposit Invoice in Payments]
   +----> [AI Kickoff Email Notification]
```

## 3. Reputation & Social Proof
```
[Appointment marked 'Completed']
   |
   V
[GP_APPOINTMENT_COMPLETED Hook]
   |
   V
[Trigger Review Request SMS]
   |
   V
[Client Leaves Review] ----> [Review Feed Updated]
   |
   V
[AI Suggested Reply Generated]
```
