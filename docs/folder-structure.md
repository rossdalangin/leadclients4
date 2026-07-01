# GrowthPress Ecosystem: Folder & File Architecture

This map outlines the modular structure of the GrowthPress Business Operating System.

## 1. GrowthPress Theme (`wp-content/themes/growthpress/`)
- `header.php`: Global navigation with glassmorphism logic.
- `footer.php`: High-authority conversion footer.
- `functions.php`: Theme setup and Advanced Customizer registration.
- `style.css`: Master design system and responsive grid.
- `template-strategy.php`: Specialized landing page template for coaches/consultants.
- `single-gp_treatment.php`: Individual dental treatment layout.
- `single-gp_property.php`: Luxury real estate listing layout.

## 2. GrowthPress Core Plugin (`wp-content/plugins/growthpress-core/`)
### `/admin/` (Business Control Suite)
- `class-growthpress-dashboard.php`: SaaS-style dashboard and Niche Setup Wizard.
- `class-growthpress-settings.php`: Global API and Branding configuration.
- `class-growthpress-content-studio.php`: AI strategy and asset generator.
- `class-growthpress-reports.php`: Data-driven ROI and conversion tracking.
- `class-growthpress-proposals-admin.php`: Unified business proposal manager.
- `class-growthpress-shortcodes.php`: Visual shortcode library for admins.

### `/includes/` (Operational Engines)
- `class-growthpress-ai.php`: Centralized GPT-4 logic and persona management.
- `class-growthpress-crm.php`: Lead management, Kanban board, and automated tasking.
- `class-growthpress-booking.php`: Multi-staff scheduling engine and waiting lists.
- `class-growthpress-portal.php`: Secure client dashboard and document hub.
- `class-growthpress-conversion.php`: Scarcity, urgency, and social proof components.
- `class-growthpress-api.php`: Secure REST API endpoints (Leads, Missed Calls).
- `class-growthpress-seo.php`: Automated Schema and dynamic title optimization.

### `/modules/` (Industry-Specific Logic)
- Each folder (e.g., `solar/`, `legal/`, `dental/`) contains a specialized class with unique calculators, triage logic, and custom post types tailored to that niche.

### `/assets/` (Visual Assets)
- `/css/admin-dashboard.css`: Premium admin UI styling.
- `/js/admin-dashboard.js`: Kanban drag-and-drop and AJAX setup.
- `/js/frontend.js`: Behavioral tracking, exit intent, and quiz logic.

## 3. Documentation (`docs/`)
- Master technical specifications, marketing toolkits, and success roadmaps.
- `database-schema-deep-dive.md`: Granular mapping of CPTs and meta keys.
- `page-builder-guide.md`: Integration for Elementor, Bricks, and Gutenberg.
- `team-collaboration-guide.md`: CRM protocols and team workflows.
- `system-scalability-multisite.md`: Agency guide for scaling to 100+ clients.
