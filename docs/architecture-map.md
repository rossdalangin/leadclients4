# GrowthPress v6.3: Technical Architecture & Omni-Intelligence Lead Flow

The GrowthPress OS operates on a "Hooks-First" asynchronous architecture designed for enterprise-grade scalability.

## 🔄 The Omni-Intelligence Lead Lifecycle

```mermaid
graph TD
    A[Prospect Landing] -->|Interaction| B[Intelligence Intake]
    B -->|gp_lead_captured| C{Neural Core}

    subgraph "The Brain (Asynchronous)"
    C -->|GPT-4| D[Sentiment & Urgency Triage]
    C -->|Neural Analysis| E[Lead Intent & Merit Scoring]
    C -->|Market Data| F[Autonomous Action Planning]
    end

    D --> G[Strategic Command CRM]
    E --> G
    F --> G

    G -->|High Urgency| H[Priority SMS Alert]
    G -->|Automation| I[5-Day Nurture Sequence]

    subgraph "Closing Protocol"
    H --> J[Booking Engine]
    I --> J
    J --> K[Secure Client Portal]
    K --> L[AI Proposal Acceptance]
    L --> M[Project/Case Kickoff]
    end
```

## 🔐 Security & Data Sovereignty
- **Encryption**: All client portal sessions use `AES-256-GCM` authenticated encryption.
- **Access Control**: Role-based capabilities combined with WP Nonce and Bearer Token verification for all REST endpoints.
- **Asynchronicity**: AI processing is offloaded to WP-Cron and background AJAX to ensure sub-1s frontend response times.

## 🛠️ Modular Niche Architecture
Each of the 10 industry modules follows a strict separation of concerns:
1.  **CPT Layer**: Specialized data structures (e.g., `gp_property` for Real Estate).
2.  **Tool Layer**: Interactive shortcodes (e.g., `gp_tax_estimator` for Accounting).
3.  **Intelligence Layer**: Custom AI prompts and triage logic calibrated for the specific industry.

---

*Status: Architecture Validated. v6.3 Definitive.*
