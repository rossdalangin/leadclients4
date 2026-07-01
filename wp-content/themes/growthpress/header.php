<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Lexend:wght@400;700;800;900&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php if(get_theme_mod('gp_enable_announcement')): ?>
    <!-- Strategic Announcement Node -->
    <div class="gp-announcement-bar" style="background:var(--primary); color:white; padding:12px; text-align:center; font-size:11px; font-weight:950; letter-spacing:1px; text-transform:uppercase;">
        <?php echo esc_html(get_theme_mod('gp_announcement_text')); ?>
    </div>
<?php endif; ?>
<header id="masthead" class="site-header gp-reveal floating-nav">
	<div class="container" style="display:flex; justify-content:<?php echo get_theme_mod('gp_header_layout', 'space-between'); ?>; align-items:center; width: 100%;">
		<div class="site-branding" style="<?php echo get_theme_mod('gp_header_layout') === 'center' ? 'flex:1;' : ''; ?>">
			<?php if(has_custom_logo()) { the_custom_logo(); } else { echo '<a href="' . home_url() . '" style="text-decoration:none; color:inherit;"><h2 style="margin:0; font-weight:950; letter-spacing:-0.08em; font-family:var(--font-heading); font-size:28px;">' . get_bloginfo('name') . '<span style="font-weight:300; opacity:0.3;"> OS</span></h2></a>'; } ?>
		</div>
		<nav id="site-navigation" class="main-navigation">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id'        => 'primary-menu',
                'container'      => false,
                'fallback_cb'    => false,
			) );
			?>
		</nav>
        <div class="header-cta" style="display: flex; gap: 30px; align-items: center;">
            <div id="gp-theme-toggle" class="theme-toggle" title="Toggle Dark/Light Mode" style="display:flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:12px; background:rgba(0,0,0,0.03); border:1px solid var(--border);">
                <svg class="sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                <svg class="moon" style="display:none;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </div>
            <a href="<?php echo esc_url(get_theme_mod('gp_header_cta_link', '/book-now')); ?>" class="gp-btn" style="padding: 1rem 2.5rem; font-size: 11px; border-radius:100px;">
                <?php echo esc_html(get_theme_mod('gp_header_cta_text', 'Secure My Slot')); ?>
            </a>
        </div>
	</div>
</header>
<style>
    .theme-toggle { cursor: pointer; color: var(--text); opacity: 0.8; transition: all 0.4s var(--ease-out-expo); }
    .theme-toggle:hover { opacity: 1; transform: rotate(15deg) scale(1.1); background: rgba(0,0,0,0.08); }
    #masthead.is-scrolled { height: 80px; background: rgba(var(--surface-rgb), 0.95); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
</style>
<script>
    const toggle = document.getElementById('gp-theme-toggle');
    const sun = toggle.querySelector('.sun');
    const moon = toggle.querySelector('.moon');

    if (localStorage.getItem('gp_theme') === 'dark') {
        document.body.classList.add('dark-theme');
        sun.style.display = 'none';
        moon.style.display = 'block';
    }

    toggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-theme');
        const isDark = document.body.classList.contains('dark-theme');
        localStorage.setItem('gp_theme', isDark ? 'dark' : 'light');
        sun.style.display = isDark ? 'none' : 'block';
        moon.style.display = isDark ? 'block' : 'none';
    });

    let lastScroll = 0;
    window.addEventListener('scroll', () => {
        const header = document.getElementById('masthead');
        const currentScroll = window.pageYOffset;

        if (currentScroll > 50) { header.classList.add('is-scrolled'); }
        else { header.classList.remove('is-scrolled'); }

        if (currentScroll > lastScroll && currentScroll > 200) {
            header.style.transform = 'translateY(-120%)';
        } else {
            header.style.transform = 'translateY(0)';
        }
        lastScroll = currentScroll;
    });
</script>
<div id="content" class="site-content grainy-bg">
