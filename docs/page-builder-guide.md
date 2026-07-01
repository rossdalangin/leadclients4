# GrowthPress Page Builder Integration Guide

The GrowthPress Business OS is designed to be builder-agnostic, providing native support for the world's most popular WordPress page builders.

## 1. Gutenberg (Block Editor)
- **Patterns**: Navigate to the Block Inserter (+) > Patterns > **GrowthPress**. We've provided pre-designed hero sections and lead-capture blocks.
- **Dynamic Blocks**: Use the "Shortcode" block to deploy any system feature (e.g., `[gp_solar_calculator]`).

## 2. Elementor Pro
- **Custom Category**: Look for the **GrowthPress OS** category in the Elementor widget panel.
- **Lead Form Widget**: Use our native Elementor widget for the primary intake form to customize styles (colors, borders, typography) directly in the Elementor UI.
- **Dynamic Tags**: You can use Elementor's dynamic tags to pull `_gp_lead_score` or `_gp_pipeline_value` into your custom admin templates.

## 3. Bricks Builder
- **Native Elements**: GrowthPress registers elements directly into the Bricks builder.
- **Conditionals**: Use Bricks' query loops to display "High Intent" leads or "Upcoming Appointments" in custom dashboard layouts.

## 4. Shortcode Global Library
If using a custom theme or other builders, use these universal shortcodes:
- `[gp_lead_form]`: High-converting intake form.
- `[gp_quiz_lead_form]`: Niche-aware qualification quiz.
- `[gp_booking_form]`: Multi-staff calendar.
- `[gp_client_portal]`: Secure customer dashboard.
- `[gp_urgency_banner]`: Dynamic scarcity alert.
