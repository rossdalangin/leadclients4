# GrowthPress Personas & User Flow Maps

## 1. The High-Ticket Prospect
**Goal:** Solve a pressing problem (Legal, Medical, Construction) with high-confidence professionals.

```mermaid
sequenceDiagram
    participant P as Prospect
    participant AI as AI Assistant
    participant B as Booking Engine
    participant S as Staff
    P->>AI: "I have a legal emergency."
    AI->>P: Analyzes urgency & Practice Area
    AI->>P: Suggests Immediate Discovery Call
    P->>B: Selects Time & Confirms
    B->>S: Notification + AI Lead Score (9.5/10)
    S->>P: Discovery Call Completed
```

## 2. The Multi-Location Business Owner
**Goal:** Centralize lead flow and track team performance across branches.

```mermaid
graph LR
    L1[Lead Dallas] --> C[Central CRM]
    L2[Lead Houston] --> C
    C --> AI[AI Triage & Route]
    AI --> B1[Dallas Branch Rep]
    AI --> B2[Houston Branch Rep]
    C --> D[Executive Dashboard]
    D --> ROI[ROI & Growth Report]
```

## 3. The Agency / White-Label Admin
**Goal:** Deploy a premium OS for a client and manage their settings.

1. **Deployment:** Install Theme & Plugin.
2. **Setup:** Run Niche Setup Wizard (One-Click).
3. **Customization:** Set Brand Colors & Logo in Customizer.
4. **Activation:** Input OpenAI & Twilio Keys.
5. **Handover:** Client gets access to 'Shortcode Library' and 'Content Studio'.
