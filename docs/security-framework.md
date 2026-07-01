# GrowthPress Security & Infrastructure Framework

GrowthPress is built with an enterprise-first security mindset, ensuring that high-ticket client data (Medical, Legal, Financial) is handled with extreme care.

## 1. Authentication & Authorization
- **Capability-Based Access**: All administrative menus, AJAX handlers, and REST routes are protected by `manage_options` capability checks. Only authorized administrators or designated roles can access the CRM or Settings.
- **REST Security**: External integrations use a Bearer Token system. Without a matching `growthpress_api_token` in the header, all requests are rejected.
- **Role Scoping**: The Client Portal is strictly limited to the data associated with the logged-in user's email address.

## 2. Integrity Protections
- **WordPress Nonces**: Every frontend and backend form (Lead capture, Quiz, Booking, Settings) is protected by unique nonces. This prevents CSRF (Cross-Site Request Forgery) attacks.
- **Input Sanitization**: All user-submitted data is passed through appropriate WordPress sanitization functions (`sanitize_text_field`, `sanitize_email`, `sanitize_textarea_field`) before being stored or used in queries.
- **Output Escaping**: Data displayed in the dashboard or frontend is escaped using `esc_html`, `esc_attr`, and `esc_url` to prevent XSS (Cross-Site Scripting).

## 3. Data Privacy
- **Encrypted Keys**: Sensitive API keys (OpenAI, Twilio, Stripe) are stored as options and, where possible, displayed as password fields in the UI to prevent shoulder-surfing.
- **Audit Logging**: Every sensitive action (Lead routing, Status changes, Proposal generation) is logged in the **System Activity Feed** with a timestamp and user ID.

## 4. AI Security
- **Spam Triage**: Before being processed by the GPT-4 engine, every lead inquiry passes through an AI-powered security filter to identify and block automated spam, protecting your API costs and CRM integrity.
- **Async Processing**: Heavy AI tasks are offloaded to background events to ensure site performance and prevent timeout-based vulnerabilities.
