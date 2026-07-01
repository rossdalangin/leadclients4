jQuery(document).ready(function($) {
    // Neural Preloader Logic
    var $preloader = $('#gp-preloader');
    if ($preloader.length) {
        $('.preloader-fill').css('width', '100%');
        setTimeout(function() {
            $preloader.addClass('fade-out');
            $('body').addClass('os-synchronized');
        }, 2600);
    }

    // Scroll-Triggered Parallax Depth v6.3
    $(window).scroll(function() {
        var scrolled = $(window).scrollTop();
        $('.gp-hero').css('background-position', 'center ' + (scrolled * 0.45) + 'px');
        $('.grainy-bg').css('background-position', '0 ' + (scrolled * 0.1) + 'px');

        if (scrolled > 70) {
            $('.site-header').addClass('scrolled');
        } else {
            $('.site-header').removeClass('scrolled');
        }
    });

    // Elite Form Sequence
    $(document).on('submit', '.gp-form', function(e) {
        e.preventDefault();
        var $btn = $(this).find('button');
        var originalText = $btn.text();
        $btn.prop('disabled', true).text('EXECUTING NEURAL TRIAGE...');

        $.post(gp_ajax.ajaxurl, {
            action: $(this).data('action'),
            lead_name: $(this).find('input[name="lead_name"]').val(),
            lead_email: $(this).find('input[name="lead_email"]').val(),
            lead_msg: $(this).find('textarea[name="lead_msg"]').val(),
            nonce: $(this).find('input[name="nonce"]').val()
        }, function(res) {
            if (res.success) {
                $('.gp-form').fadeOut(500, function() {
                    $(this).html('<div style="text-align:center; padding:100px 40px;"><div style="font-size:6rem; margin-bottom:40px;">💎</div><h2 class="text-gradient" style="font-size:3rem; margin-bottom:20px;">STRATEGY NODE ACTIVATED</h2><p style="font-size:1.2rem; opacity:0.7;">AI analysis complete. A specialist is preparing your bespoke roadmap.</p></div>').fadeIn();
                });
            } else {
                $btn.prop('disabled', false).text(originalText);
            }
        });
    });

    // Smooth Reveal Engine v6.3
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('gp-revealed');
                entry.target.style.transitionDelay = (entry.target.dataset.delay || 0) + 'ms';
            }
        });
    }, { threshold: 0.12, rootMargin: "0px 0px -100px 0px" });

    $('.glass-card, section, h1, h2, .gp-btn, .wp-block-column').each(function(i) {
        $(this).addClass('gp-reveal').attr('data-delay', i * 100);
        revealObserver.observe(this);
    });

    // Ultra Magnetic Physics v6.3 (High-Fidelity Spring Interaction)
    $(document).on('mousemove', '.gp-btn, #gp-chat-launcher, .staff-avatar, .gp-magnetic', function(e) {
        const rect = this.getBoundingClientRect();
        const x = (e.clientX - rect.left - rect.width / 2) / 3;
        const y = (e.clientY - rect.top - rect.height / 2) / 3;

        $(this).css({
            'transform': `translate(${x}px, ${y}px) scale(1.06) rotate(${x/12}deg)`,
            'box-shadow': '0 30px 60px rgba(0,0,0,0.2), 0 0 20px var(--primary-glow)',
            'transition': 'transform 0.15s cubic-bezier(0.33, 1, 0.68, 1)',
            'z-index': '50'
        });
    }).on('mouseleave', '.gp-btn, #gp-chat-launcher, .staff-avatar, .gp-magnetic', function() {
        $(this).css({
            'transform': '',
            'box-shadow': '',
            'transition': 'all 0.7s cubic-bezier(0.16, 1, 0.3, 1)',
            'z-index': ''
        });
    });

    // Mobile Navigation Slide-over
    $('#gp-mobile-trigger').on('click', function() { $('#gp-mobile-menu').addClass('active'); });
    $('#gp-mobile-close').on('click', function() { $('#gp-mobile-menu').removeClass('active'); });

    // Real-time Authority Feed Engine
    function initAuthorityFeed() {
        if ($('body').hasClass('wp-admin') || !gp_ajax.authority_enabled) return;

        setInterval(function() {
            $.post(gp_ajax.ajaxurl, { action: 'gp_get_authority_feed' }, function(res) {
                if(res.success && res.data.length > 0) {
                    const event = res.data[Math.floor(Math.random() * res.data.length)];
                    showAuthorityToast(event);
                }
            });
        }, parseInt(gp_ajax.authority_interval));
    }

    function showAuthorityToast(event) {
        const icon = event.type === 'conversion' ? '⚡' : (event.type === 'authority' ? '⭐' : '🗓️');
        const $toast = $(`
            <div class="gp-authority-toast glass-card" style="position:fixed; bottom:110px; left:30px; z-index:10000; padding:20px 25px; border-radius:20px; min-width:300px; display:flex; gap:15px; align-items:center; box-shadow:0 30px 60px rgba(0,0,0,0.1); border-left: 8px solid var(--primary); transform:translateY(100px); opacity:0; transition:all 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
                <div style="font-size:24px;">${icon}</div>
                <div>
                    <div style="font-size:11px; font-weight:950; letter-spacing:1px; opacity:0.4; text-transform:uppercase; margin-bottom:4px;">${event.title}</div>
                    <div style="font-size:13px; font-weight:700; color:var(--secondary); line-height:1.3;">${event.msg}</div>
                    <div style="font-size:9px; font-weight:800; opacity:0.3; margin-top:5px; text-transform:uppercase;">Uplink: ${event.time}</div>
                </div>
            </div>
        `).appendTo('body');

        setTimeout(() => $toast.css({'transform':'translateY(0)', 'opacity':'1'}), 100);
        setTimeout(() => {
            $toast.css({'transform':'translateY(100px)', 'opacity':'0'});
            setTimeout(() => $toast.remove(), 600);
        }, 6000);
    }

    initAuthorityFeed();
});
