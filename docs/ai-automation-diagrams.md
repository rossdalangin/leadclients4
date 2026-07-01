# GrowthPress: AI & Automation Visual Logic Diagrams

These diagrams provide a visual map of the autonomous loops within the GrowthPress OS.

## 1. The Intelligent Triage Loop
```mermaid
graph TD
    A[Visitor] -->|Completes Quiz| B{AI Spam Filter}
    B -->|Spam| C[Discard & Log]
    B -->|Legit| D[Create gp_lead]
    D --> E[Async AI Sentiment Scan]
    E --> F{High Urgency?}
    F -->|Yes| G[Assign to Admin + Urgent Task]
    F -->|No| H[Assign to Sales Rep]
    G --> I[Omnichannel 5-Day Nurture]
    H --> I
```

## 2. The Appointment & Retention Loop
```mermaid
graph LR
    A[Lead Books Call] --> B[AI Reminder SMS 24h]
    B --> C[Discovery Call Completed]
    C --> D{Close Deal?}
    D -->|Yes| E[Accept Proposal in Portal]
    D -->|No| F[AI Reactivation Scout Loop]
    E --> G[Automated Review Request]
    G --> H[Review Published to Social Proof]
```

## 3. The Content & SEO Loop
```mermaid
graph TD
    A[Market Topic] --> B[AI Content Studio]
    B --> C[Generate Authority Guide]
    C --> D[Publish to Knowledge Base]
    D --> E[SEO Traffic Capture]
    E --> F[Internal Hook to [gp_quiz_lead_form]]
    F --> G[New Intelligent Lead]
```
