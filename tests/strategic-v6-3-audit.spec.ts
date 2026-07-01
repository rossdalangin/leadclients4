import { test, expect } from '@playwright/test';

/**
 * GrowthPress v6.3: Master Strategic Operational Audit
 *
 * This comprehensive test suite mirrors the 'Master Tutorial & Operational Blueprint'.
 * It verifies the full lifecycle from system activation to ROI realization.
 */
test.describe('GrowthPress v6.3 Full Lifecycle Strategic Audit', () => {

    test('Phase 1: License Authority & Node Authentication', async ({ page }) => {
        // [Blueprint: Step 1.4] Initialize License Node via Hub
        await page.goto('/wp-admin/admin.php?page=gp-hub');
        await expect(page.locator('h1')).toContainText('License Authority & Issuance Hub');

        // Provisioning cryptographic anchor
        await page.fill('#customer_email', 'enterprise-v63@growthpress.io');
        await page.selectOption('#license_tier', 'Enterprise');
        await page.click('button:has-text("INITIALIZE LICENSE ISSUANCE")');

        await expect(page.locator('#issuance-res')).toContainText('License Node instantiated');
        const licenseKey = await page.locator('.wp-list-table tr:last-child td:first-child').innerText();

        // [Blueprint: Step 2.4] Authenticating Node in Core Settings
        await page.goto('/wp-admin/admin.php?page=growthpress-settings');
        await page.click('a[href="#tab-license"]');
        await page.fill('input[name="growthpress_license_key"]', licenseKey);
        await page.click('button:has-text("Authenticate Node")');

        await expect(page.locator('#tab-license')).toContainText('STATUS: active');
    });

    test('Phase 2: Niche Calibration & Dashboard Intelligence', async ({ page }) => {
        // [Blueprint: Step 1.3] Niche Selection & Recalibration
        await page.goto('/wp-admin/admin.php?page=growthpress-dashboard');
        await page.selectOption('#gp-niche-switcher', 'medical');

        // Verify Instructional Tooltips (Dashboard Intelligence Layer)
        const neuralStatus = page.locator('div[title*="heartbeat of your connected AI providers"]');
        await expect(neuralStatus).toBeVisible();

        // [Blueprint: Step 6.4] Strategic Command Analysis
        await expect(page.locator('.main-col')).toContainText('Strategic Command: AI Recommendations');
    });

    test('Phase 3: AI Content Studio & Propagational Guidance', async ({ page }) => {
        // [Blueprint: Step 6.2] Intelligence Propagation
        await page.goto('/wp-admin/admin.php?page=growthpress-studio');

        // Verify Strategic Context Box
        await expect(page.locator('.glass-card')).toContainText('Strategic Context: Content Propagation');

        // Configure and Generate specialized treatment node
        await page.selectOption('#gp-content-type', 'treatment');
        await page.click('button[data-tone="Technical"]');
        await page.fill('#gp-content-topic', 'Neurological Triage Protocols');
        await page.click('button:has-text("INITIALIZE GENERATION")');

        // Verify presence of sync nodes
        await expect(page.locator('.sync-btn:has-text("Sync to Treatments")')).toBeVisible();
    });

    test('Phase 4: Ecosystem Management & Relational Integrity', async ({ page }) => {
        // [Blueprint: Step 3.1] System Seeding Verification
        await page.goto('/wp-admin/admin.php?page=gp-hub-generator');
        await expect(page.locator('h4')).toContainText('Strategic Context: Ecosystem Generation');

        // Check relational map of Custom Post Types
        await page.goto('/wp-admin/admin.php?page=growthpress-ecosystem');
        await expect(page.locator('h1')).toContainText('Business OS Ecosystem Map');

        // Verify node connectivity (e.g., Leads and Specialists)
        await expect(page.locator('.glass-card', { hasText: 'Leads' })).toBeVisible();
        await expect(page.locator('.glass-card', { hasText: 'Specialists' })).toBeVisible();
    });

    test('Phase 5: Master OS Configuration & Realization Parameters', async ({ page }) => {
        // [Blueprint: Step 2] Neural & Financial Calibration
        await page.goto('/wp-admin/admin.php?page=growthpress-settings');

        // Verify multi-AI failover context
        await page.click('a[href="#tab-ai"]');
        await expect(page.locator('#tab-ai')).toContainText('Multi-Intelligence Routing');

        // Verify operational connective tissue (Integrations)
        await page.click('a[href="#tab-integrations"]');
        await expect(page.locator('#tab-integrations')).toContainText('Operational Ecosystem');

        // Verify autonomous realization (Automations)
        await page.click('a[href="#tab-automations"]');
        await expect(page.locator('#tab-automations')).toContainText('Autonomous Realization');
    });

    test('Phase 6: Frontend Experience & Aesthetic Authority', async ({ page }) => {
        // [Blueprint: Step 4] User Journey / Intake Realization
        await page.goto('/');

        // Verify Cinematic Authority Node
        await expect(page.locator('.gp-hero')).toBeVisible();
        await expect(page.locator('.eyebrow')).toContainText('ELITE BUSINESS OS v6.3');

        // Verify Medical Niche Intelligence Node (since we switched in Phase 2)
        await expect(page.locator('section:has-text("MEDICAL INTELLIGENCE NODE")')).toBeVisible();

        // Verify Footer Real-time Status (The "Heartbeat")
        await expect(page.locator('footer')).toContainText('SYSTEM ONLINE');
        await expect(page.locator('footer')).toContainText('NEURAL LINK ACTIVE');
    });

});
