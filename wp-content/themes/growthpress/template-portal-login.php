<?php
/**
 * Template Name: Secure Portal Authentication
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="min-height: 80vh; display:flex; align-items:center;">
    <div class="container" style="width: 100%;">
        <div style="max-width:800px; margin:0 auto;" class="gp-reveal">
            <?php if ( ! is_user_logged_in() ) : ?>
                <div class="glass-card" style="padding:100px 80px; text-align:center;">
                    <div style="font-size:5rem; margin-bottom:40px;">🔐</div>
                    <h1 class="text-gradient" style="font-size:3.5rem; margin-bottom:20px;"><?php echo esc_html(get_theme_mod('gp_portal_headline', 'Portal Command')); ?></h1>
                    <p style="font-size:1.2rem; opacity:0.6; margin-bottom:50px;"><?php echo esc_html(get_theme_mod('gp_portal_subheadline', 'Identify yourself to access proprietary growth metrics, financial ledgers, and strategic documents.')); ?></p>

                    <div style="text-align:left; background:rgba(0,0,0,0.02); padding:50px; border-radius:30px; border:1px solid var(--border);">
                        <?php wp_login_form( array(
                            'redirect' => home_url( '/client-portal/' ),
                            'label_username' => 'UPLINK IDENTITY',
                            'label_password' => 'SECURE KEY',
                            'label_log_in'   => 'AUTHENTICATE SESSION',
                            'remember'       => true
                        ) ); ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="glass-card" style="padding:80px; text-align:center;">
                    <h2 class="text-gradient">Session Authenticated</h2>
                    <p style="margin:20px 0 40px;">Your secure uplink is active. Redirecting to the command center...</p>
                    <a href="<?php echo home_url('/client-portal/'); ?>" class="gp-btn">Enter Portal</a>
                    <script>window.location.href = '<?php echo home_url("/client-portal/"); ?>';</script>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
    #loginform label { display:block; font-size:10px; font-weight:950; letter-spacing:2px; margin-bottom:10px; opacity:0.5; }
    #loginform input[type="text"], #loginform input[type="password"] {
        height:70px; border-radius:15px; margin-bottom:25px; background:white; border:1px solid #E2E8F0; width:100%; padding:0 25px; font-size:16px; font-weight:700;
    }
    #loginform input[type="submit"] {
        width:100%; height:80px; border-radius:100px; background:var(--primary); color:white; border:none; font-weight:950; letter-spacing:2px; cursor:pointer; margin-top:20px; transition:0.3s;
    }
    #loginform input[type="submit"]:hover { transform: scale(1.02); }
</style>

<?php get_footer(); ?>
