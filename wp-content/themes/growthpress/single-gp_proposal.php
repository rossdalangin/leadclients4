<?php
/**
 * Single Proposal Template - GrowthPress Elite
 * This template renders the binding strategic proposal for client execution.
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <?php while ( have_posts() ) : the_post();
            $status = get_post_meta(get_the_ID(), '_gp_proposal_status', true) ?: 'Draft';
            $val = get_post_meta(get_the_ID(), '_proposal_value', true);
            $expires = get_post_meta(get_the_ID(), '_proposal_expires', true);
            ?>
            <div style="max-width:900px; margin:0 auto;">
                <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:60px;">
                    <div>
                        <div style="font-size:10px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">STRATEGIC ARCHITECTURE PROPOSAL</div>
                        <h1 class="text-gradient" style="margin:0; font-size:3.5rem; letter-spacing:-0.05em;"><?php the_title(); ?></h1>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:2px; margin-bottom:10px;">PROPOSAL STATUS</div>
                        <div style="background:<?php echo $status === 'Accepted' ? '#10B981' : 'var(--primary)'; ?>; color:white; padding:8px 25px; border-radius:30px; font-size:11px; font-weight:950;"><?php echo strtoupper($status); ?></div>
                    </div>
                </div>

                <article class="glass-card gp-reveal" style="padding:80px; border-radius:50px;">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:40px; margin-bottom:80px; padding-bottom:40px; border-bottom:1px solid rgba(0,0,0,0.05);">
                        <div>
                            <div style="font-size:10px; font-weight:950; opacity:0.3; letter-spacing:1px; margin-bottom:10px;">INVESTMENT VALUE</div>
                            <div style="font-size:32px; font-weight:950; color:var(--secondary);">$<?php echo number_format($val); ?></div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:10px; font-weight:950; opacity:0.3; letter-spacing:1px; margin-bottom:10px;">OFFER EXPIRES</div>
                            <div style="font-size:18px; font-weight:700; color:#EF4444;"><?php echo $expires ?: 'ASAP'; ?></div>
                        </div>
                    </div>

                    <div class="entry-content" style="font-size:1.2rem; line-height:1.9; color:var(--text);">
                        <?php the_content(); ?>
                    </div>

                    <?php if($status !== 'Accepted'): ?>
                        <div style="margin-top:100px; padding:60px; background:#F8FAFC; border-radius:40px; border:1px solid #E2E8F0; text-align:center;">
                            <h3 style="margin-bottom:20px;">Execute Strategic Agreement</h3>
                            <p style="opacity:0.6; margin-bottom:40px;">By executing this proposal, you authenticate the engagement of proprietary services and initiate the project kickoff sequence.</p>
                            <button class="gp-btn" style="width:100%; height:85px; font-size:20px;" onclick="executeProposal(<?php echo get_the_ID(); ?>)">AUTHENTICATE & EXECUTE</button>
                        </div>
                    <?php else: ?>
                        <div style="margin-top:100px; padding:60px; background:#F0FDF4; border-radius:40px; border:1px solid #DCFCE7; text-align:center;">
                            <div style="font-size:4rem; margin-bottom:20px;">✅</div>
                            <h3 style="color:#166534;">AGREEMENT EXECUTED</h3>
                            <p style="color:#166534; opacity:0.7;">This strategic architecture has been digitally signed and the kickoff sequence is active.</p>
                        </div>
                    <?php endif; ?>
                </article>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<script>
function executeProposal(id) {
    if(!confirm("Initialize binding agreement and kickoff sequence?")) return;
    jQuery.post(gp_ajax.ajaxurl, { action: 'gp_accept_proposal', proposal_id: id }, function(res) {
        if(res.success) {
            location.reload();
        }
    });
}
</script>

<?php get_footer(); ?>
