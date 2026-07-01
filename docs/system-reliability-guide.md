# System Reliability & Maintenance Guide

To ensure your Business OS remains an elite growth engine, follow these operational best practices.

## 1. API Health & Monitoring
- **OpenAI Connectivity**: Check the **System Diagnostics** on the dashboard weekly. If the API shows 'Offline', verify your billing balance at platform.openai.com.
- **Twilio Credits**: Ensure your SMS balance is topped up to prevent 'Abandoned Inquiry' follow-ups from failing.

## 2. Updates & Syncing
- **Theme/Plugin Updates**: Always perform updates in a staging environment first.
- **Ecosystem Sync**: After updating your brand color or headlines in the Customizer, always click **"Regenerate Core Assets"** to push those changes to your Home and Services pages.

## 3. Data Backups
- Your CRM data (`gp_lead`, `gp_appointment`) is stored in the standard WordPress database. We recommend daily off-site backups (e.g., via ManageWP or UpdraftPlus) to protect your high-ticket pipeline.

## 4. Security Audits
- **Bearer Token Rotation**: For maximum security, rotate your `growthpress_api_token` in Settings every 90 days.
- **User Permissions**: Perform a monthly audit of WordPress users. Ensure only active sales reps have access to the Kanban and Strategic Briefs.

## 5. Performance Optimization
- GrowthPress uses **Async AI Processing** to keep the frontend fast. If you notice slow lead ingestion, check your server's WP-Cron performance or install an object cache (e.g., Redis).
