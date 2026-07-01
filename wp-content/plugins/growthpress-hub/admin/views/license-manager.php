<?php
/**
 * GrowthPress License Manager View - Elite v6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

$license_hub = GrowthPress_License::get_instance();
$licenses = $license_hub->get_all_licenses();
?>

<div class="wrap growthpress-license-manager gp-reveal">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px; padding:30px; background:rgba(255,255,255,0.6); border-radius:30px; border:1px solid var(--glass-border);">
        <h1 style="margin:0;">License Authority & Issuance Hub</h1>
        <div style="background:var(--secondary); color:white; padding:8px 16px; border-radius:30px; font-size:11px; font-weight:900; letter-spacing:1px;">AUTHORITY NODE v6.3</div>
    </div>

    <div class="glass-card" style="max-width:1100px; background:#f0fdf4; border-left:5px solid #16a34a; margin-bottom:30px; padding:20px;">
        <h4 style="margin:0 0 10px 0; color:#166534;">💡 Success Pattern: License Distribution</h4>
        <p style="margin:0; font-size:13px; color:#166534; line-height:1.5;">Issued licenses are cryptographic anchors that validate ecosystem nodes. High-performance firms use this hub to provision enterprise access for client-partners and maintain recurring equity trajectories.</p>
    </div>

    <div style="display:grid; grid-template-columns: 1.5fr 1fr; gap:30px;">
        <!-- License Issuance Form -->
        <div class="glass-card">
            <h3 class="text-gradient">Issue Strategic License</h3>
            <p style="opacity:0.6; margin-bottom:30px;">Provision a new cryptographic node for a client or internal team member.</p>

            <form id="gp-issue-license-form">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label>Customer Email</label></th>
                        <td><input type="email" id="customer_email" name="customer_email" class="regular-text" required placeholder="customer@enterprise.com"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Strategic Tier</label></th>
                        <td>
                            <select id="license_tier" name="license_tier" style="width:100%;">
                                <option value="Lite">Lite (CRM & Booking Only)</option>
                                <option value="Pro">Pro (AI Content & Payments)</option>
                                <option value="Elite" selected>Elite (Full Ecosystem & Multi-AI)</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <div style="margin-top:30px;">
                    <button type="submit" class="gp-btn" style="background:var(--primary); color:white; width:100%; height:60px; border-radius:15px;">INITIALIZE LICENSE ISSUANCE</button>
                </div>
            </form>
            <div id="issuance-res" style="margin-top:20px; padding:20px; border-radius:15px; display:none;"></div>
        </div>

        <!-- System Stats -->
        <div class="glass-card" style="background:var(--primary-glow); text-align:center; display:flex; flex-direction:column; justify-content:center;">
            <div style="font-size:10px; font-weight:950; color:var(--primary); letter-spacing:2px; margin-bottom:15px;">TOTAL AUTHENTICATED NODES</div>
            <div style="font-size:6rem; font-weight:950; color:var(--primary); line-height:1;"><?php echo count($licenses); ?></div>
            <p style="font-size:13px; opacity:0.6; margin-top:20px;">Active cryptographic signatures in the central registry.</p>
        </div>
    </div>

    <!-- Active License Registry -->
    <div class="glass-card" style="margin-top:40px; padding:0; overflow:hidden;">
        <div style="padding:40px 40px 20px;">
            <h3 class="text-gradient" style="margin:0;">Strategic License Registry</h3>
            <p style="opacity:0.6; margin-top:10px;">Monitoring and managing issued node authentication keys.</p>
        </div>
        <table class="wp-list-table widefat fixed striped" style="border:none; background:transparent;">
            <thead>
                <tr style="text-align:left; background: rgba(0,0,0,0.02);">
                    <th style="padding:20px; font-weight:950; font-size:10px; opacity:0.4; letter-spacing:1px;">LICENSE KEY</th>
                    <th style="padding:20px; font-weight:950; font-size:10px; opacity:0.4; letter-spacing:1px;">CUSTOMER</th>
                    <th style="padding:20px; font-weight:950; font-size:10px; opacity:0.4; letter-spacing:1px;">TIER / CAPS</th>
                    <th style="padding:20px; font-weight:950; font-size:10px; opacity:0.4; letter-spacing:1px;">ACTIVATIONS</th>
                    <th style="padding:20px; font-weight:950; font-size:10px; opacity:0.4; letter-spacing:1px;">STATUS</th>
                    <th style="padding:20px; font-weight:950; font-size:10px; opacity:0.4; letter-spacing:1px;">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php if($licenses): foreach($licenses as $l):
                    $status = get_post_meta($l->ID, '_license_status', true);
                    $email = get_post_meta($l->ID, '_customer_email', true);
                    $tier = get_post_meta($l->ID, '_license_tier', true);
                    $caps = get_post_meta($l->ID, '_capabilities', true);
                    $activations = get_post_meta($l->ID, '_activation_count', true) ?: 0;
                    ?>
                    <tr>
                        <td style="padding:20px; font-family:monospace; font-weight:800; font-size:14px;"><?php echo $l->post_title; ?></td>
                        <td style="padding:20px; font-weight:600;"><?php echo $email; ?></td>
                        <td style="padding:20px;">
                            <span style="background:var(--primary-glow); color:var(--primary); padding:4px 12px; border-radius:10px; font-size:11px; font-weight:900; display:block; margin-bottom:5px; text-align:center;"><?php echo strtoupper($tier); ?></span>
                            <div style="font-size:8px; opacity:0.4; line-height:1.2; text-transform:uppercase; letter-spacing:1px;">
                                <?php echo is_array($caps) ? implode(' • ', $caps) : 'STANDARD'; ?>
                            </div>
                        </td>
                        <td style="padding:20px; font-weight:900; font-size:18px; text-align:center;"><?php echo $activations; ?></td>
                        <td style="padding:20px;">
                            <span style="background:<?php echo $status === 'active' ? '#D1FAE5' : '#FEF2F2'; ?>; color:<?php echo $status === 'active' ? '#065F46' : '#991B1B'; ?>; padding:4px 12px; border-radius:10px; font-size:11px; font-weight:900;">
                                <?php echo strtoupper($status); ?>
                            </span>
                        </td>
                        <td style="padding:20px;">
                            <?php if($status === 'active'): ?>
                                <button type="button" class="button button-link-delete" onclick="revokeLicense(<?php echo $l->ID; ?>)">Revoke Node</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="5" style="padding:40px; text-align:center; opacity:0.3; font-weight:900; letter-spacing:2px;">NO LICENSE NODES FOUND IN REGISTRY</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#gp-issue-license-form').on('submit', function(e) {
        e.preventDefault();
        const res = $('#issuance-res').fadeIn().text('SYNCHRONIZING WITH REGISTRY...').css({'background':'#F8FAFC', 'color':'#64748B'});
        const data = {
            action: 'gp_issue_license',
            customer_email: $('#customer_email').val(),
            license_tier: $('#license_tier').val(),
            gp_nonce: '<?php echo wp_create_nonce("gp_admin_nonce"); ?>'
        };

        $.post(ajaxurl, { ...data, action: 'gp_hub_issue_license' }, function(response) {
            if (response.success) {
                res.text(response.data).css({'background':'#F0FDF4', 'color':'#10B981'});
                setTimeout(() => location.reload(), 2000);
            } else {
                res.text(response.data).css({'background':'#FEF2F2', 'color':'#EF4444'});
            }
        });
    });
});

function revokeLicense(id) {
    if(!confirm('DANGER: Revoking this node will disable system intelligence for the associated client. Proceed?')) return;
    jQuery.post(ajaxurl, {
        action: 'gp_hub_revoke_license',
        license_id: id,
        gp_nonce: '<?php echo wp_create_nonce("gp_admin_nonce"); ?>'
    }, function() {
        location.reload();
    });
}
</script>
