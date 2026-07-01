jQuery(document).ready(function($) {
    // Niche Setup
    window.setupNiche = function() {
        if ( !confirm("This will automatically generate core business pages (Home, Services, Contact) and sample demo data for your niche. Continue?") ) return;

        var niche = $('#gp-niche-select').val();
        $.post(ajaxurl, {
            action: 'gp_setup_niche',
            niche: niche,
            gp_nonce: gp_admin.nonce
        }, function(response) {
            if (response.success) {
                alert("OS Initialized Successfully! Core pages created.");
                location.reload();
            }
        });
    };

    // Global Micro-interactions
    $('.glass-card').addClass('gp-reveal');

    // Kanban Drag & Drop
    if ($('.kanban-cards').length > 0) {
        $('.kanban-card').css('transition', 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)');

        $('.kanban-card').on('click', function(e) {
            if($(e.target).closest('a, button').length) return;
            const leadId = $(this).data('id');
            const overlay = $('<div class="gp-modal-overlay"><div class="gp-modal"><div class="modal-loader" style="text-align:center; padding:100px; font-weight:950; letter-spacing:2px; opacity:0.4;">NEURAL BRIEF SYNCHRONIZING...</div></div></div>').appendTo('body');

            $.post(ajaxurl, {
                action: 'gp_get_lead_brief',
                lead_id: leadId,
                gp_nonce: gp_admin.nonce
            }, function(res) {
                if(res.success) {
                    overlay.find('.gp-modal').html(res.data.html);
                }
            });

            overlay.on('click', function(e) {
                if($(e.target).is('.gp-modal-overlay')) $(this).fadeOut(function(){ $(this).remove(); });
            });
        });

        $('.kanban-card').draggable({
            revert: "invalid",
            helper: "clone",
            cursor: "move",
            start: function() { $(this).hide(); },
            stop: function() { $(this).show(); }
        });

        $('.kanban-col').droppable({
            accept: ".kanban-card",
            drop: function(event, ui) {
                var leadId = ui.draggable.data('id');
                var newStage = $(this).data('stage');
                var $cardsContainer = $(this).find('.kanban-cards');

                ui.draggable.appendTo($cardsContainer).css({
                    top: '0px',
                    left: '0px'
                });

                $.post(ajaxurl, {
                    action: 'gp_update_lead_stage',
                    lead_id: leadId,
                    stage: newStage,
                    gp_nonce: gp_admin.nonce
                }, function(response) {
                    if (!response.success) alert("Failed to update lead stage.");
                });
            }
        });
    }

    // Lead Generation for Content Studio
    window.generateContent = function() {
        var $out = $('#gp-studio-output');
        var $actions = $('#gp-studio-actions');
        var tone = $('.tone-btn.active').data('tone');
        var topic = $('#gp-content-topic').val();
        var $loader = $('#studio-loader');
        var $placeholder = $('#studio-placeholder');
        var $ping = $('#studio-status-ping');

        if(!topic) { alert('Calibration error: Topic focus required.'); return; }

        $placeholder.hide();
        $out.find('.ai-response').remove();
        $loader.fadeIn();
        $actions.hide();
        $ping.css('background', '#10B981');

        $.post(ajaxurl, {
            action: 'gp_generate_content',
            content_type: $('#gp-content-type').val(),
            topic: topic,
            tone: tone,
            gp_nonce: gp_admin.nonce
        }, function(res) {
            $loader.hide();
            $ping.css('background', 'rgba(255,255,255,0.2)');
            if (res.success) {
                const $responseNode = $('<div class="ai-response"></div>').appendTo($out);
                let i = 0;
                const text = res.data;
                const speed = 2; // Terminal typing speed

                function typeEffect() {
                    if (i < text.length) {
                        $responseNode.append(text.charAt(i) === "\n" ? "<br>" : text.charAt(i));
                        i++;
                        $out.scrollTop($out[0].scrollHeight);
                        setTimeout(typeEffect, speed);
                    } else {
                        $('#preview-body').html(res.data.replace(/\n/g, '<br>'));
                        $('#preview-title').text(topic.toUpperCase());
                        $actions.css('display', 'grid');
                        $ping.css('background', '#10B981').addClass('status-ping-active');
                    }
                }
                typeEffect();
            } else {
                $out.append('<div class="ai-response" style="color:#EF4444;">ENGINE ERROR: ' + res.data + '</div>');
            }
        });
    };

    // Ecosystem Sync
    window.syncAsset = function(type) {
        const title = $('#gp-content-topic').val();
        const content = $('#gp-studio-output').find('.ai-response').text();

        if (!title || !content) {
            alert('Calibration error: Generate content node first before synchronization.');
            return;
        }

        const btn = $(`.sync-btn[onclick="syncAsset('${type}')"]`);
        const originalText = btn.text();
        btn.text('SYNCHRONIZING...').prop('disabled', true).css('opacity', 0.5);

        $.post(ajaxurl, {
            action: 'gp_sync_to_kb',
            title: title,
            content: content,
            type: type,
            gp_nonce: gp_admin.nonce
        }, function(res) {
            if (res.success) {
                btn.text('SYNCED').css({'background':'#10B981', 'color':'#FFF', 'opacity':1});
                setTimeout(() => {
                    btn.text(originalText).css({'background':'', 'color':'', 'opacity':''}).prop('disabled', false);
                }, 2000);
            } else {
                alert('Synchronization failed.');
                btn.text(originalText).prop('disabled', false).css('opacity', 1);
            }
        });
    };

    window.copyStudioOutput = function() {
        const text = $('#gp-studio-output').find('.ai-response').text();
        navigator.clipboard.writeText(text).then(() => {
            alert('Intelligence copied to clipboard.');
        });
    };
});
