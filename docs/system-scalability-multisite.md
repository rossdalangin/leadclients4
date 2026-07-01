# Scaling with GrowthPress: WordPress Multisite Guide

For agencies managing 50+ clients, the recommended deployment architecture is **WordPress Multisite**. This allows you to manage the entire "Fleet" of Business OS instances from a single network dashboard.

## 1. Network Setup
1. Enable Multisite in `wp-config.php`.
2. Network-activate the **GrowthPress Theme** and **GrowthPress Core Plugin**.
3. Use a plugin like "WP Ultimo" or "Cloner" to create a master "Industry Template" (e.g., a pre-configured Solar template).

## 2. Centralized AI Management
- **Network Settings**: You can choose to use a single "Master OpenAI Key" for all sub-sites or require each client to provide their own.
- **Global Schema**: Use the `gp_ai_system_prompt` filter at the network level to enforce a consistent agency "voice" across all client instances.

## 3. Maintenance & Sync
- When you update the **GrowthPress Core** plugin, use the **Network Upgrade** feature to push the new operational features (like the new Kanban or Waiting List) to all clients simultaneously.
- Use the `gp_regenerate_pages` hook via a CLI script to refresh core assets for the entire network in one command.

## 4. Resource Allocation
- **Database**: Each client site has its own set of tables (e.g., `wp_2_posts`), ensuring data isolation for high-ticket Legal or Medical clients.
- **Security**: Network-level nonces and capability checks prevent cross-site data leakage.
