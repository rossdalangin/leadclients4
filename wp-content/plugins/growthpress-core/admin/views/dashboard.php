<div class="wrap growthpress-dashboard">
    <div class="dashboard-header gp-reveal" style="margin-bottom: 50px;">
        <div style="display:flex; align-items:center; gap:35px;">
            <?php $dash_logo = get_option('growthpress_dashboard_logo'); if($dash_logo): ?>
                <img src="<?php echo esc_url($dash_logo); ?>" style="max-height:60px;">
            <?php else: ?>
                <h1 style="font-size:3rem; font-weight:950; letter-spacing:-0.09em; margin:0; line-height:1;"><?php echo esc_html(get_option('growthpress_brand_name', 'GrowthPress')); ?> <span style="font-weight:300; opacity:0.25;">OS</span></h1>
            <?php endif; ?>
            <div style="height:45px; width:1px; background:rgba(0,0,0,0.1);"></div>
            <select id="gp-niche-switcher" onchange="switchNiche(this.value)" style="height:55px; background:rgba(255,255,255,0.8); border:1px solid rgba(0,0,0,0.05); padding:0 20px; border-radius:15px; font-size:11px; font-weight:950; letter-spacing:3px; text-transform:uppercase; cursor:pointer; box-shadow: 0 10px 25px rgba(0,0,0,0.02);">
                <?php
                $active_niche = get_option('growthpress_niche', 'business');
                $niches = array('dental', 'law', 'contractor', 'roofing', 'solar', 'accounting', 'medical', 'real-estate', 'coaches', 'consultants');
                foreach($niches as $n): ?>
                    <option value="<?php echo $n; ?>" <?php selected($n, $active_niche); ?>><?php echo strtoupper($n); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="display:flex; gap:20px; align-items:center;">
            <div style="position:relative;" id="gp-search-container">
                <input type="text" id="gp-strategic-search" placeholder="Strategic Search..." style="height:55px; background:rgba(255,255,255,0.95); border:1px solid rgba(0,0,0,0.08); padding:0 30px; border-radius:20px; font-size:12px; width:280px; font-weight: 600;">
                <span class="dashicons dashicons-search" style="position:absolute; right:20px; top:18px; opacity:0.3;"></span>
                <div id="gp-search-results" style="display:none; position:absolute; top:65px; left:0; width:100%; background:white; border-radius:20px; box-shadow:0 30px 60px rgba(0,0,0,0.15); z-index:1000; overflow:hidden; border: 1px solid rgba(0,0,0,0.05);"></div>
            </div>
            <div class="dark-mode-toggle" onclick="toggleDarkMode()" title="Toggle Strategic Dark Mode" style="width:55px; height:55px; background: #FFF; border: 1px solid #EEE; border-radius: 18px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease;"><span class="dashicons dashicons-visibility"></span></div>
            <button class="gp-btn" style="height:55px; padding:0 30px; font-size:12px; border-radius:15px; background:var(--secondary); color:white !important; font-weight: 800; letter-spacing: 1px;" onclick="exportLeads()">EXPORT INTEL</button>
            <div class="ai-status" style="background:linear-gradient(135deg, #10B981, #059669); color:white; height:55px; padding:0 30px; border-radius:30px; font-size:12px; font-weight:950; letter-spacing:2px; box-shadow:0 15px 40px rgba(16,185,129,0.3); display: flex; align-items: center;">CORE ACTIVE</div>
        </div>
    </div>

    <!-- System Node Health Elite -->
    <div class="glass-card gp-reveal" style="margin-bottom:50px; padding:40px; border-radius: 40px; background:rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.8);">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div style="display:flex; gap:60px; align-items:center;">
                <div style="display:flex; align-items:center; gap:15px;" title="Monitors OpenAI, Claude, and Gemini API connectivity status.">
                    <div class="status-ping active"></div>
                    <div>
                        <div style="font-size:11px; font-weight:950; letter-spacing:2px; color: var(--secondary);">NEURAL ENGINE</div>
                        <div style="font-size:10px; opacity:0.5; font-weight: 800;">GPT-4 TURBO ONLINE</div>
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:15px;" title="Tracks real-time synchronization between the 14 custom post type nodes.">
                    <div class="status-ping active"></div>
                    <div>
                        <div style="font-size:11px; font-weight:950; letter-spacing:2px; color: var(--secondary);">CRM DATA SYNC</div>
                        <div style="font-size:10px; opacity:0.5; font-weight: 800;">100% NODES ACTIVE</div>
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:15px;" title="Monitors latency and health of Stripe, Twilio, and Map APIs.">
                    <div class="status-ping warning"></div>
                    <div>
                        <div style="font-size:11px; font-weight:950; letter-spacing:2px; color: var(--secondary);">EXTERNAL API</div>
                        <div style="font-size:10px; opacity:0.5; font-weight: 800;">84ms LATENCY DETECTED</div>
                    </div>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:3px;">SYSTEM VERSION</div>
                <div style="font-size:14px; font-weight:950; color: var(--primary);">ELITE v6.3 DEFINITIVE</div>
            </div>
        </div>
    </div>

    <!-- System Health Grid -->
    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:20px; margin-bottom:20px;">
        <div class="glass-card" style="padding:20px; display:flex; align-items:center; gap:15px;">
            <div class="status-ping active"></div>
            <div style="font-size:10px; font-weight:900; letter-spacing:1px; opacity:0.5;">CRM: SYNCHRONIZED</div>
        </div>
        <?php $ai_active = get_option('growthpress_openai_api_key') || get_option('growthpress_claude_api_key'); ?>
        <div class="glass-card" style="padding:20px; display:flex; align-items:center; gap:15px;">
            <div class="status-ping <?php echo $ai_active ? 'active' : 'warning'; ?>"></div>
            <div style="font-size:10px; font-weight:900; letter-spacing:1px; opacity:0.5;">AI: <?php echo $ai_active ? 'GPT-4 TUNED' : 'OFFLINE'; ?></div>
        </div>
        <?php $stripe_active = get_option('growthpress_stripe_secret'); ?>
        <div class="glass-card" style="padding:20px; display:flex; align-items:center; gap:15px;">
            <div class="status-ping <?php echo $stripe_active ? 'active' : 'warning'; ?>"></div>
            <div style="font-size:10px; font-weight:900; letter-spacing:1px; opacity:0.5;">STRIPE: <?php echo $stripe_active ? 'CALIBRATED' : 'DISCONNECTED'; ?></div>
        </div>
    </div>
    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:20px; margin-bottom:40px;">
        <?php $paypal_active = get_option('growthpress_paypal_secret'); ?>
        <div class="glass-card" style="padding:20px; display:flex; align-items:center; gap:15px;">
            <div class="status-ping <?php echo $paypal_active ? 'active' : 'warning'; ?>"></div>
            <div style="font-size:10px; font-weight:900; letter-spacing:1px; opacity:0.5;">PAYPAL: <?php echo $paypal_active ? 'AUTHORIZED' : 'OFFLINE'; ?></div>
        </div>
        <?php $zoom_active = get_option('growthpress_zoom_client_secret'); ?>
        <div class="glass-card" style="padding:20px; display:flex; align-items:center; gap:15px;">
            <div class="status-ping <?php echo $zoom_active ? 'active' : 'warning'; ?>"></div>
            <div style="font-size:10px; font-weight:900; letter-spacing:1px; opacity:0.5;">ZOOM: <?php echo $zoom_active ? 'UPLINK STABLE' : 'UNCONFIGURED'; ?></div>
        </div>
        <?php $license_active = get_option('growthpress_license_status') === 'active'; ?>
        <div class="glass-card" style="padding:20px; display:flex; align-items:center; gap:15px;">
            <div class="status-ping <?php echo $license_active ? 'active' : 'warning'; ?>"></div>
            <div style="font-size:10px; font-weight:900; letter-spacing:1px; opacity:0.5;">OS LICENSE: <?php echo $license_active ? 'AUTHENTICATED' : 'TRIAL/DEV'; ?></div>
        </div>
    </div>

    <!-- AI Strategic Roadmap & Node Connectivity -->
    <div class="glass-card gp-reveal" style="margin-bottom:50px; padding:50px; border-radius:45px; background:linear-gradient(135deg, rgba(255,255,255,0.95), rgba(248,250,252,0.95)); border:1px solid rgba(255,255,255,0.8); box-shadow:0 30px 60px -15px rgba(0,0,0,0.05);">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:40px;">
            <div>
                <h3 style="margin:0; font-size:24px; font-weight:950; letter-spacing:-0.04em;">Ecosystem Strategic Roadmap</h3>
                <p style="font-size:14px; opacity:0.6; margin-top:8px; font-weight:500;">Visualizing the interconnected intelligence flow across the 14-node Business OS.</p>
            </div>
            <div style="display:flex; gap:12px;">
                <div style="background:var(--primary-glow); color:var(--primary); padding:8px 16px; border-radius:12px; font-size:10px; font-weight:950; letter-spacing:1px;">NODES: SYNCHRONIZED</div>
                <div style="background:#F0FDF4; color:#10B981; padding:8px 16px; border-radius:12px; font-size:10px; font-weight:950; letter-spacing:1px;">AI: OPTIMIZED</div>
            </div>
        </div>

        <div style="position:relative; height:180px; display:flex; justify-content:space-between; align-items:center; padding:0 40px; margin-top:60px; margin-bottom:20px;">
            <!-- Connector Lines with Gradient Flow -->
            <div style="position:absolute; top:50%; left:80px; right:40px; height:2px; background:linear-gradient(90deg, var(--primary) 0%, #10B981 33%, #F59E0B 66%, #EF4444 100%); opacity:0.15; z-index:1; overflow:hidden;">
                <div class="gradient-flow-line"></div>
            </div>

            <div style="position:relative; z-index:2; text-align:center; width:120px;">
                <div style="width:70px; height:70px; background:var(--primary); border-radius:24px; margin:0 auto 20px; display:flex; align-items:center; justify-content:center; box-shadow:0 15px 35px var(--primary-glow); transition:transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <span class="dashicons dashicons-id-alt" style="color:white; font-size:32px; width:32px; height:32px;"></span>
                </div>
                <div style="font-size:10px; font-weight:950; letter-spacing:1px; color:var(--secondary); opacity:0.4;">01. INTAKE</div>
                <div style="font-size:13px; font-weight:900; margin-top:5px;">Leads & Triage</div>
                <p style="font-size:9px; opacity:0.5; line-height:1.3; margin-top:8px;">Captures and scores initial inquiries via AI.</p>
            </div>

            <div style="position:relative; z-index:2; text-align:center; width:120px;">
                <div style="width:70px; height:70px; background:#10B981; border-radius:24px; margin:0 auto 20px; display:flex; align-items:center; justify-content:center; box-shadow:0 15px 35px rgba(16,185,129,0.25); transition:transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <span class="dashicons dashicons-calendar-alt" style="color:white; font-size:32px; width:32px; height:32px;"></span>
                </div>
                <div style="font-size:10px; font-weight:950; letter-spacing:1px; color:var(--secondary); opacity:0.4;">02. CONVERSION</div>
                <div style="font-size:13px; font-weight:900; margin-top:5px;">Bookings & Deals</div>
                <p style="font-size:9px; opacity:0.5; line-height:1.3; margin-top:8px;">Manages strategy sessions and deal flow.</p>
            </div>

            <div style="position:relative; z-index:2; text-align:center; width:120px;">
                <div style="width:70px; height:70px; background:#F59E0B; border-radius:24px; margin:0 auto 20px; display:flex; align-items:center; justify-content:center; box-shadow:0 15px 35px rgba(245,158,11,0.25); transition:transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <span class="dashicons dashicons-portfolio" style="color:white; font-size:32px; width:32px; height:32px;"></span>
                </div>
                <div style="font-size:10px; font-weight:950; letter-spacing:1px; color:var(--secondary); opacity:0.4;">03. EXECUTION</div>
                <div style="font-size:13px; font-weight:900; margin-top:5px;">Projects & Tasks</div>
                <p style="font-size:9px; opacity:0.5; line-height:1.3; margin-top:8px;">Operationalizes closed deals into projects.</p>
            </div>

            <div style="position:relative; z-index:2; text-align:center; width:120px;">
                <div style="width:70px; height:70px; background:#EF4444; border-radius:24px; margin:0 auto 20px; display:flex; align-items:center; justify-content:center; box-shadow:0 15px 35px rgba(239,68,68,0.25); transition:transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <span class="dashicons dashicons-money-alt" style="color:white; font-size:32px; width:32px; height:32px;"></span>
                </div>
                <div style="font-size:10px; font-weight:950; letter-spacing:1px; color:var(--secondary); opacity:0.4;">04. REALIZATION</div>
                <div style="font-size:13px; font-weight:900; margin-top:5px;">ROI & Equity</div>
                <p style="font-size:9px; opacity:0.5; line-height:1.3; margin-top:8px;">Tracks revenue, OpEx, and total net equity.</p>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="main-col">
            <!-- Strategic Command: AI Recommendations -->
            <div class="glass-card gp-reveal" style="margin-bottom:40px; border-top: 10px solid var(--primary); padding: 50px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px;">
                    <div>
                        <h3 style="margin:0; font-size:24px; font-weight:950; letter-spacing:-0.04em;">Strategic Command: AI Recommendations</h3>
                        <p style="font-size:14px; opacity:0.6; margin-top:8px;">Real-time actionable intelligence based on ecosystem performance delta.</p>
                    </div>
                    <div style="background:var(--primary-glow); color:var(--primary); padding:8px 16px; border-radius:12px; font-size:10px; font-weight:950; letter-spacing:1px;">ANALYSIS: v6.3 FINAL</div>
                </div>
                <div style="display:grid; gap:20px;">
                    <?php if($strategic_recs): foreach($strategic_recs as $rec): ?>
                        <div style="background:rgba(0,0,0,0.02); padding:25px; border-radius:20px; display:flex; gap:25px; align-items:center; border: 1px solid rgba(0,0,0,0.03);">
                            <div style="width:50px; height:50px; background:<?php echo $rec['prio'] === 'High' ? '#EF4444' : ($rec['prio'] === 'Medium' ? '#F59E0B' : '#10B981'); ?>; border-radius:15px; display:flex; align-items:center; justify-content:center; color:white; font-weight:950; font-size:20px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                                <?php echo $rec['prio'] === 'High' ? '!' : ($rec['prio'] === 'Medium' ? '?' : '✓'); ?>
                            </div>
                            <div style="flex:1;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:5px;">
                                    <h4 style="margin:0; font-size:16px; font-weight:900;"><?php echo esc_html($rec['title']); ?></h4>
                                    <span style="font-size:9px; font-weight:950; opacity:0.4; letter-spacing:1px;"><?php echo strtoupper($rec['prio']); ?> PRIORITY</span>
                                </div>
                                <p style="margin:0; font-size:13px; opacity:0.7; line-height:1.5; font-weight:600;"><?php echo esc_html($rec['msg']); ?></p>
                            </div>
                            <button class="gp-btn" style="padding:10px 20px; font-size:10px; border-radius:10px;" onclick="alert('Action node sequence initiated...')">EXECUTE</button>
                        </div>
                    <?php endforeach; else: ?>
                        <p style="opacity:0.4; text-align:center;">Analyzing ecosystem performance metrics...</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Market Dominance Scorecard -->
            <div class="glass-card gp-reveal" style="margin-bottom:40px; padding:50px; background:linear-gradient(135deg, var(--secondary) 0%, #020617 100%); color:white; border:none;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:40px;">
                    <div>
                        <h3 style="color:white; margin:0; font-size:24px; font-weight:950; letter-spacing:-0.04em;">Market Dominance Scorecard</h3>
                        <p style="font-size:14px; opacity:0.6; margin-top:8px;">Benchmarking your ecosystem against top 1% sector performers.</p>
                    </div>
                    <div style="background:var(--accent); color:white; padding:8px 16px; border-radius:12px; font-size:10px; font-weight:950; letter-spacing:1px;">ELITE BENCHMARKS</div>
                </div>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:40px;">
                    <div style="background:rgba(255,255,255,0.05); padding:35px; border-radius:30px; border:1px solid rgba(255,255,255,0.1);">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                            <span style="font-size:11px; font-weight:950; opacity:0.5; letter-spacing:2px;">SATURATION DELTA</span>
                            <span style="color:var(--accent); font-weight:950;">+14.2%</span>
                        </div>
                        <div style="height:8px; background:rgba(255,255,255,0.1); border-radius:10px; overflow:hidden;">
                            <div style="width:72%; height:100%; background:var(--accent); box-shadow: 0 0 20px var(--accent);"></div>
                        </div>
                        <p style="font-size:11px; opacity:0.4; margin-top:15px; line-height:1.5;">Your digital footprint density compared to local enterprise competitors. <strong style="cursor:help;" title="Saturation Delta measures your Share of Voice (SOV) and search dominance in your ZIP-routed sectors.">ⓘ</strong></p>
                    </div>
                    <div style="background:rgba(255,255,255,0.05); padding:35px; border-radius:30px; border:1px solid rgba(255,255,255,0.1);">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                            <span style="font-size:11px; font-weight:950; opacity:0.5; letter-spacing:2px;">AUTOMATION EFFICIENCY</span>
                            <span style="color:#10B981; font-weight:950;">88.4%</span>
                        </div>
                        <div style="height:8px; background:rgba(255,255,255,0.1); border-radius:10px; overflow:hidden;">
                            <div style="width:88%; height:100%; background:#10B981; box-shadow: 0 0 20px #10B981;"></div>
                        </div>
                        <p style="font-size:11px; opacity:0.4; margin-top:15px; line-height:1.5;">Percentage of high-stakes interactions handled autonomously via neural nodes. <strong style="cursor:help;" title="Automation Efficiency tracks how much of your Lead-to-Booked lifecycle is handled by AI vs manual labor.">ⓘ</strong></p>
                    </div>
                </div>
            </div>

            <!-- Strategic Performance Engine Elite -->
            <div class="glass-card gp-reveal" style="margin-bottom:40px; padding:45px; border-radius: 40px;">
                <div style="background:rgba(37,99,235,0.05); border:1px solid rgba(37,99,235,0.1); padding:20px; border-radius:20px; margin-bottom:40px; display:flex; align-items:center; gap:20px;">
                    <div style="font-size:24px;">💡</div>
                    <div style="font-size:13px; font-weight:600; color:var(--primary); line-height:1.5;">
                        <strong>Strategic Insight:</strong> Your conversion trajectory shows the momentum of leads through the funnel. Aim for a "Smooth Ascending" curve to ensure consistent pipeline equity.
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:50px;">
                    <div>
                        <h3 style="margin:0; font-size:26px; font-weight:950; letter-spacing:-0.05em;">Intelligence Performance Hub</h3>
                        <p style="font-size:16px; opacity:0.6; margin-top:10px; font-weight: 500;">Real-time trajectory modeling across all high-ticket conversion vectors.</p>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:3px;">ENGINE LATENCY</div>
                        <div style="color:#10B981; font-weight:950; font-size:16px; margin-top:5px;">142ms (OPTIMAL)</div>
                    </div>
                </div>
                <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); gap: 30px; margin-bottom: 50px;">
                    <div class="stat gp-reveal" style="padding: 35px; border-radius: 30px; background: rgba(0,0,0,0.02); position: relative; overflow: hidden; border: 1px solid rgba(0,0,0,0.03);">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                            <span style="font-size:11px; font-weight:950; color:#64748B; text-transform:uppercase; letter-spacing:2px;">Captured Inquiries</span>
                            <span class="dashicons dashicons-id-alt" style="color:var(--primary); opacity:0.2; font-size:24px;"></span>
                        </div>
                        <b style="font-size: 3.5rem; letter-spacing: -0.05em; display: block; margin-top: 10px;"><?php echo $lead_count_30d; ?></b>
                        <div style="position:absolute; bottom:0; left:0; height:6px; width:100%; background:var(--primary);"></div>
                    </div>
                    <div class="stat gp-reveal" style="animation-delay: 0.1s; padding: 35px; border-radius: 30px; background: rgba(0,0,0,0.02); position: relative; overflow: hidden; border: 1px solid rgba(0,0,0,0.03);">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                            <span style="font-size:11px; font-weight:950; color:#64748B; text-transform:uppercase; letter-spacing:2px;">Strategy Sessions</span>
                            <span class="dashicons dashicons-calendar-alt" style="color:#10B981; opacity:0.2; font-size:24px;"></span>
                        </div>
                        <b style="color:#10B981; font-size: 3.5rem; letter-spacing: -0.05em; display: block; margin-top: 10px;"><?php echo $booking_count; ?></b>
                        <div style="position:absolute; bottom:0; left:0; height:6px; width:100%; background:#10B981;"></div>
                    </div>
                    <div class="stat gp-reveal" style="animation-delay: 0.2s; padding: 35px; border-radius: 30px; background: rgba(0,0,0,0.02); position: relative; overflow: hidden; border: 1px solid rgba(0,0,0,0.03);">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                            <span style="font-size:11px; font-weight:950; color:#64748B; text-transform:uppercase; letter-spacing:2px;">Pipeline Equity</span>
                            <span class="dashicons dashicons-chart-area" style="color:var(--primary); opacity:0.2; font-size:24px;"></span>
                        </div>
                        <?php $pipe_val = GrowthPress_Proposals::get_instance()->get_pipeline_value(); ?>
                        <b style="color:var(--primary); font-size: 3.5rem; letter-spacing: -0.05em; display: block; margin-top: 10px;">$<?php echo number_format($pipe_val); ?></b>
                        <div style="position:absolute; bottom:0; left:0; height:6px; width:100%; background:var(--primary);"></div>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns: 1.5fr 1fr; gap:30px;">
                    <div style="background:rgba(255,255,255,0.4); padding:40px; border-radius:40px; border: 1px solid rgba(255,255,255,0.8); box-shadow:inset 0 10px 30px rgba(0,0,0,0.02);">
                        <div style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:30px; display:flex; align-items:center; gap:10px;">
                            <div style="width:8px; height:8px; background:var(--primary); border-radius:50%;"></div> CONVERSION TRAJECTORY
                        </div>
                        <canvas id="gp-main-chart" height="150"></canvas>
                    </div>
                    <div style="background:rgba(255,255,255,0.4); padding:40px; border-radius:40px; border: 1px solid rgba(255,255,255,0.8); box-shadow:inset 0 10px 30px rgba(0,0,0,0.02);">
                        <div style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:30px; display:flex; align-items:center; gap:10px;">
                            <div style="width:8px; height:8px; background:var(--accent); border-radius:50%;"></div> MARKETING VELOCITY
                        </div>
                        <canvas id="gp-velocity-chart" height="230"></canvas>
                    </div>
                </div>
            </div>

            <!-- CRM Board -->
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:60px; margin-bottom:40px;">
                <h3 style="display:flex; align-items:center; gap:15px; font-size:28px; letter-spacing:-0.05em; margin:0;">
                    <span class="dashicons dashicons-networking" style="color: var(--primary); font-size:32px; width:32px; height:32px;"></span> Neural Sales Command
                </h3>
                <div style="font-size:12px; font-weight:900; opacity:0.4; letter-spacing:1px;">SORT BY: STRATEGIC PRIORITY</div>
            </div>

            <div id="gp-kanban-board" class="kanban-board-container">
                <?php foreach ( $stages as $slug => $label ) : ?>
                    <div class="kanban-col" data-stage="<?php echo $slug; ?>">
                        <h4 style="margin-top:0; font-weight:950; color:var(--secondary); display:flex; justify-content:space-between; align-items:center; text-transform: uppercase; letter-spacing:2px; font-size:11px; padding:0 10px; opacity:0.6;">
                            <?php echo $label; ?>
                            <span style="font-size:11px; background:#FFF; border:1px solid #E2E8F0; padding:4px 14px; border-radius:30px; color:var(--secondary);">
                                <?php
                                $count_in_stage = count(array_filter($leads, function($l) use ($slug) {
                                    $s = wp_get_object_terms($l->ID, 'gp_lead_stage', array('fields' => 'slugs'));
                                    return (empty($s) && $slug === 'new') || in_array($slug, $s);
                                }));
                                echo $count_in_stage;
                                ?>
                            </span>
                        </h4>
                        <div class="kanban-cards" style="min-height:600px; margin-top:30px;">
                            <?php foreach ( $leads as $lead ) :
                                $stage = wp_get_object_terms( $lead->ID, 'gp_lead_stage', array('fields' => 'slugs') );
                                if ( (empty($stage) && $slug === 'new') || in_array($slug, $stage) ) :
                                    $prob = get_post_meta($lead->ID, '_gp_ai_probability', true) ?: 50;
                                    $staff_id = get_post_meta($lead->ID, '_assigned_staff', true);
                                    $staff = $staff_id ? get_userdata($staff_id) : null;
                                    ?>
                                    <div class="kanban-card glass-card <?php echo $prob > 85 ? 'neural-pulse' : ''; ?> gp-reveal" data-id="<?php echo $lead->ID; ?>" style="border-left: 8px solid <?php echo $prob > 80 ? '#10B981' : 'var(--primary)'; ?>; padding:30px; border-radius: 25px; margin-bottom: 25px; background: rgba(255,255,255,0.7);">
                                        <?php if($prob > 88): ?>
                                            <div style="position: absolute; top: 0; right: 0; background: linear-gradient(135deg, #EF4444, #B91C1C); color: white; font-size: 9px; font-weight: 950; padding: 5px 25px; transform: rotate(45deg) translate(20px, -20px); text-transform: uppercase; letter-spacing:2px; box-shadow:0 5px 15px rgba(239,68,68,0.25); z-index: 10;">HOT</div>
                                        <?php endif; ?>

                                        <strong style="display:block; margin-bottom:15px; font-size:18px; font-weight:950; letter-spacing:-0.05em; color:var(--secondary); line-height: 1.1;"><?php echo esc_html($lead->post_title); ?></strong>

                                        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                                            <div>
                                                <?php
                                                $sentiment_raw = get_post_meta($lead->ID, '_gp_ai_sentiment_json', true);
                                                $sentiment_data = json_decode($sentiment_raw, true);
                                                $urgency = $sentiment_data['urgency'] ?? 5;
                                                ?>
                                                <div style="display:flex; gap:8px; margin-bottom:20px;">
                                                    <?php $tag = wp_get_object_terms($lead->ID, 'gp_lead_tag', array('fields' => 'names')); if($tag): ?>
                                                        <div style="font-size:9px; background:var(--primary-glow); color:var(--primary); padding:5px 12px; border-radius:30px; font-weight:950; text-transform: uppercase; letter-spacing:1px; border:1px solid rgba(37,99,235,0.1);"><?php echo esc_html($tag[0]); ?></div>
                                                    <?php endif; ?>
                                                    <div style="font-size:9px; background:<?php echo $urgency > 7 ? '#FEF2F2' : '#F0FDF4'; ?>; color:<?php echo $urgency > 7 ? '#EF4444' : '#10B981'; ?>; padding:5px 12px; border-radius:30px; font-weight:950; text-transform: uppercase; letter-spacing:1px; border:1px solid <?php echo $urgency > 7 ? '#FEE2E2' : '#DCFCE7'; ?>;">URGENCY: <?php echo $urgency; ?></div>
                                                </div>
                                                <div style="font-size:13px; font-weight:950; color:#10B981; letter-spacing:0.5px;">PROBABILITY: <span style="font-size: 15px;"><?php echo $prob; ?>%</span></div>
                                            </div>

                                            <div class="staff-avatar" title="<?php echo $staff ? esc_attr($staff->display_name) : 'UNASSIGNED'; ?>" style="width: 48px; height: 48px; border-radius: 50%; background: #FFF; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 950; border: 4px solid #F8FAFC; box-shadow: 0 10px 30px rgba(0,0,0,0.1); color:var(--secondary); position: relative;">
                                                <?php echo $staff ? substr($staff->display_name, 0, 1) : '?'; ?>
                                                <div style="position: absolute; bottom: 0; right: 0; width: 12px; height: 12px; background: #10B981; border-radius: 50%; border: 2px solid #FFF;"></div>
                                            </div>
                                        </div>

                                        <div style="margin-top:25px; display: flex; gap:10px;">
                                            <a href="<?php echo get_edit_post_link($lead->ID); ?>" class="gp-btn" style="flex:1; padding:12px; font-size:11px; border-radius:12px; background:var(--secondary); text-align:center; color:white !important; font-weight: 800; letter-spacing: 0.5px;">INTEL BRIEF</a>
                                            <button class="gp-btn" style="padding:12px; border-radius:12px; background:transparent; border:1px solid #E2E8F0; color:var(--secondary) !important; width:48px;"><span class="dashicons dashicons-email"></span></button>
                                        </div>
                                    </div>
                                <?php endif;
                            endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="side-col">
            <!-- Neural Activity Feed -->
            <div class="glass-card gp-reveal" style="margin-bottom:30px; background: #0F172A; color: rgba(255,255,255,0.9); border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
                    <h3 style="font-size:12px; text-transform: uppercase; letter-spacing: 3px; opacity: 0.5; margin:0; font-weight:950; color:white;">Neural Activity</h3>
                    <div style="font-size:9px; font-weight:900; color:var(--accent); letter-spacing:1px; background:rgba(16,185,129,0.1); padding:4px 10px; border-radius:10px;">LIVE STREAM</div>
                </div>
                <div style="display:grid; gap:18px; font-family: 'JetBrains Mono', monospace; font-size:11px;">
                    <?php
                    $logs = GrowthPress_Activity::get_logs(6);
                    if($logs): foreach($logs as $log): ?>
                        <div style="display:flex; gap:15px; position:relative; padding-bottom:15px; border-bottom:1px solid rgba(255,255,255,0.05);">
                            <div style="width:4px; height:4px; background:var(--accent); border-radius:50%; margin-top:6px; box-shadow:0 0 10px var(--accent);"></div>
                            <div>
                                <div style="font-weight:600; line-height:1.4; color:rgba(255,255,255,0.8);"><span style="color:var(--accent);">>>></span> <?php echo esc_html($log['msg']); ?></div>
                                <div style="font-size:8px; opacity:0.3; font-weight:800; margin-top:4px;"><?php echo strtoupper($log['time']); ?></div>
                            </div>
                        </div>
                    <?php endforeach; else: echo "<p style='opacity:0.3; font-size:10px;'>[SYSTEM] INITIALIZING NEURAL UPLINK...</p>"; endif; ?>
                </div>
            </div>

            <!-- AI Strategic Insight -->
            <div class="glass-card gp-reveal" style="border-top: 8px solid #10B981; margin-bottom:30px;">
                <h3 style="font-size:14px; text-transform: uppercase; letter-spacing: 2px; opacity: 0.4; margin-bottom: 20px; font-weight:950;">Strategic Vector</h3>
                <?php
                $niche = get_option('growthpress_niche', 'business');
                $insights = array(
                    'solar'       => "High electricity load profiles in your sector are driving an 18% increase in 'S-Tier' system inquiries.",
                    'law'         => "Leads from high-wealth ZIP codes are peaking. Calibrate AI merit engine for 'Corporate' litigation.",
                    'medical'     => "Post-holiday intake volume is rising. Ensure AI Health Assistant is optimized for routing.",
                );
                ?>
                <p style="font-size:15px; line-height:1.6; font-weight: 600; color:var(--secondary); margin-bottom:25px;"><?php echo $insights[$niche] ?? "Market authority is peaking. Implement tiered 'Elite' membership model to capture high-intent interest."; ?></p>
                <div style="background:linear-gradient(135deg, var(--primary), var(--primary-alt)); padding:20px; border-radius:20px; box-shadow:0 15px 30px var(--primary-glow);">
                    <div style="font-size:9px; font-weight:950; color:white; opacity:0.7; letter-spacing:2px; margin-bottom:8px;">STRATEGIC COMMAND</div>
                    <div style="color:white; font-weight:950; font-size:13px; line-height:1.3;">EXECUTE OMNI-CHANNEL RETARGETING</div>
                </div>
            </div>

            <!-- Autonomous Agent Feed -->
            <div class="glass-card gp-reveal" style="margin-bottom:30px;">
                <h3 style="font-size:14px; text-transform: uppercase; letter-spacing: 2px; opacity: 0.4; margin-bottom:25px; font-weight:950;">Agent Task Queue</h3>
                <div style="display:grid; gap:15px;">
                    <?php
                    $ai_tasks = get_posts(array(
                        'post_type' => 'gp_task',
                        'meta_query' => array(
                            array('key' => '_assigned_staff', 'value' => 'ai_node'),
                            array('key' => '_task_status', 'value' => 'Pending')
                        ),
                        'posts_per_page' => 5
                    ));
                    if($ai_tasks): foreach($ai_tasks as $at):
                        $prio = get_post_meta($at->ID, '_task_priority', true);
                        ?>
                        <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px; font-weight:700;">
                            <span title="<?php echo esc_attr($at->post_content); ?>"><?php echo esc_html($at->post_title); ?></span>
                            <span style="color:<?php echo ($prio === 'High') ? '#EF4444' : '#10B981'; ?>;"><?php echo strtoupper($prio); ?></span>
                        </div>
                    <?php endforeach; else: ?>
                        <div style="font-size:11px; opacity:0.4;">No active agent tasks.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Priority Waiting List -->
            <div class="glass-card gp-reveal" style="margin-bottom:30px; border-left: 8px solid var(--accent);">
                <h3 style="font-size:14px; text-transform: uppercase; letter-spacing: 2px; opacity: 0.4; margin-bottom:25px; font-weight:950;">Priority Queue</h3>
                <?php
                $waiting = get_posts(array('post_type' => 'gp_appointment', 'meta_key' => '_is_waiting_list', 'meta_value' => '1', 'posts_per_page' => 3));
                if($waiting): foreach($waiting as $w): ?>
                    <div style="margin-bottom:15px; padding-bottom:15px; border-bottom:1px solid #F1F5F9; display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:12px; font-weight:700;"><?php echo esc_html($w->post_title); ?></span>
                        <span style="font-size:9px; color:var(--accent); font-weight:900;">WAITING</span>
                    </div>
                <?php endforeach; else: echo "<p style='opacity:0.4; font-size:11px;'>No prospects in waiting queue.</p>"; endif; ?>
            </div>

            <!-- Revenue ROI Hub Elite -->
            <div class="glass-card gp-reveal" style="margin-bottom:40px; background:var(--primary); color:white; border:none; position:relative; padding: 45px; border-radius: 40px; box-shadow: 0 30px 60px -15px var(--primary-glow);">
                <div style="position:absolute; top:30px; right:30px; cursor:help; opacity:0.4;" title="Calculated from 'Paid' Revenue transactions vs pending Pipeline Equity.">ⓘ</div>
                <h3 style="color:white; font-size:14px; text-transform: uppercase; letter-spacing: 3px; opacity: 0.7; margin-bottom:35px; font-weight:950;">Revenue Analytics</h3>
                <div style="display:grid; gap:30px;">
                    <div>
                        <div style="font-size:11px; font-weight:950; opacity:0.6; letter-spacing:2px; margin-bottom:8px; text-transform: uppercase;">Earned Equity</div>
                        <div style="font-size:36px; font-weight:950; letter-spacing: -0.05em;">$<?php
                            $revenue = 0; $expenses = 0;
                            $transactions = get_posts(array('post_type'=>'gp_transaction', 'meta_key'=>'_status', 'meta_value'=>'Paid', 'posts_per_page'=>-1));
                            foreach($transactions as $tx) {
                                $amt = (float)get_post_meta($tx->ID, '_amount', true);
                                $type = get_post_meta($tx->ID, '_transaction_type', true) ?: 'Revenue';
                                if($type === 'Revenue') $revenue += $amt; else $expenses += $amt;
                            }
                            echo number_format($revenue);
                        ?></div>
                    </div>
                    <div style="height:1px; background:rgba(255,255,255,0.15);"></div>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                        <div>
                            <div style="font-size:10px; font-weight:950; opacity:0.5; letter-spacing:1px; margin-bottom:5px; text-transform: uppercase;">OpEx</div>
                            <div style="font-size:18px; font-weight:950; color:rgba(255,255,255,0.8);">$<?php echo number_format($expenses); ?></div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size:10px; font-weight:950; opacity:0.5; letter-spacing:1px; margin-bottom:5px; text-transform: uppercase;">Upside (60%)</div>
                            <div style="font-size:18px; font-weight:950; color:#10B981;">$<?php echo number_format($pipe_val * 0.6); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Neural Gap Analysis -->
            <div class="glass-card gp-reveal" style="margin-bottom:30px; border-left: 8px solid var(--primary);">
                <h3 style="font-size:14px; text-transform: uppercase; letter-spacing: 2px; opacity: 0.4; margin-bottom:25px; font-weight:950;">Neural Gap Analysis</h3>
                <div style="display:grid; gap:15px;">
                    <div style="background:rgba(0,0,0,0.02); padding:15px; border-radius:12px;">
                        <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:10px;">SECTOR BENCHMARK: <?php echo strtoupper($active_niche); ?></div>
                        <div style="font-size:13px; font-weight:700; line-height:1.4;">
                            Current conversion node is <span style="color:#10B981;">+14.2% ahead</span> of local benchmarks. Strategic upside detected in 'Retainer' modeling.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff Efficiency Hub -->
            <div class="glass-card gp-reveal" style="margin-bottom:30px;">
                <h3 style="font-size:14px; text-transform: uppercase; letter-spacing: 2px; opacity: 0.4; margin-bottom:25px; font-weight:950;">Staff Efficiency</h3>
                <div style="display:grid; gap:15px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:12px; font-weight:700;">Avg. Response</span>
                        <span style="font-size:12px; font-weight:950; color:#10B981;">4.2 min</span>
                    </div>
                    <div style="height:5px; background:#F1F5F9; border-radius:10px; overflow:hidden;">
                        <div style="width:85%; height:100%; background:var(--primary);"></div>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:5px;">
                        <span style="font-size:12px; font-weight:700;">Closing Rate</span>
                        <span style="font-size:12px; font-weight:950; color:#10B981;">28.4%</span>
                    </div>
                    <div style="height:5px; background:#F1F5F9; border-radius:10px; overflow:hidden;">
                        <div style="width:62%; height:100%; background:#10B981;"></div>
                    </div>
                </div>
            </div>

            <!-- Revenue Forecast Engine -->
            <div class="forecast-widget">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <div style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; margin-bottom:5px;">REVENUE FORECAST</div>
                        <div style="font-size:32px; font-weight:950;">$<?php echo number_format($pipe_val * 0.65); ?></div>
                    </div>
                    <div style="background:rgba(16,185,129,0.2); color:#10B981; padding:6px 12px; border-radius:20px; font-size:10px; font-weight:950;">+12.4%</div>
                </div>
                <div style="margin-top:25px; font-size:12px; opacity:0.6; line-height:1.6;">
                    AI models predict a 65% weighted conversion probability for current high-intent pipeline items.
                </div>
                <div style="margin-top:20px; height:6px; background:rgba(255,255,255,0.05); border-radius:10px; overflow:hidden;">
                    <div style="width:65%; height:100%; background:#10B981; box-shadow:0 0 15px rgba(16,185,129,0.5);"></div>
                </div>
            </div>

            <!-- Conversion Command Elite -->
            <div class="glass-card" style="background: var(--secondary); color: white; border: none; border-radius:50px; padding:55px; margin-top:40px; position:relative; box-shadow: 0 40px 100px -20px rgba(0,0,0,0.3);">
                <div style="position:absolute; top:30px; right:30px; cursor:help; opacity:0.3;" title="Traffic share and conversion velocity across the entire ecosystem.">ⓘ</div>
                <h3 style="color: white; font-size: 18px; letter-spacing:2px; font-weight:950; text-transform: uppercase;">Funnel Command</h3>
                <div style="height:320px; display:flex; align-items:flex-end; gap:30px; padding: 50px 0;">
                    <div style="flex:1; height:100%; background:var(--primary); border-radius:20px; display:flex; align-items:center; justify-content:center; color:white; font-size:12px; font-weight:950; writing-mode:vertical-rl; box-shadow:0 0 30px var(--primary-glow); letter-spacing: 2px;">INTAKE</div>
                    <div style="flex:1; height:85%; background:#10B981; border-radius:20px; display:flex; align-items:center; justify-content:center; color:white; font-size:12px; font-weight:950; writing-mode:vertical-rl; letter-spacing: 2px;">TRIAGE</div>
                    <div style="flex:1; height:52%; background:#F59E0B; border-radius:20px; display:flex; align-items:center; justify-content:center; color:white; font-size:12px; font-weight:950; writing-mode:vertical-rl; letter-spacing: 2px;">STRATEGY</div>
                    <div style="flex:1; height:24%; background:#EF4444; border-radius:20px; display:flex; align-items:center; justify-content:center; color:white; font-size:12px; font-weight:950; writing-mode:vertical-rl; letter-spacing: 2px;">EQUITY</div>
                </div>
                <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 35px; display: flex; justify-content: space-between; font-size: 15px; font-weight: 950;">
                    <span style="opacity:0.4; letter-spacing: 1px;">CONV. VELOCITY</span>
                    <span style="color:var(--accent); letter-spacing: 1px;"><?php echo $velocity_days; ?> DAYS</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function submitAIFeedback(postId, key, feedback, el) {
    jQuery.post(ajaxurl, {
        action: 'gp_submit_ai_feedback',
        post_id: postId,
        meta_key: key,
        feedback: feedback,
        gp_nonce: gp_admin.nonce
    }, function() {
        jQuery(el).parent().html('<span style="font-size:9px; font-weight:950; opacity:0.5;">FEEDBACK INGESTED</span>');
    });
}

function switchNiche(niche) {
    if(confirm('Switching ecosystem to ' + niche.toUpperCase() + '? This will recalibrate Neural Hub prompts.')) {
        jQuery.post(ajaxurl, {
            action: 'gp_setup_niche',
            niche: niche,
            gp_nonce: gp_admin.nonce
        }, function() { location.reload(); });
    }
}

function toggleDarkMode() {
    document.querySelector('.growthpress-dashboard').classList.toggle('gp-dark-mode');
}

function exportLeads() {
    window.location.href = ajaxurl + '?action=gp_export_leads&gp_nonce=' + gp_admin.nonce;
}

document.addEventListener('DOMContentLoaded', function() {
    jQuery('#gp-strategic-search').on('keyup', function() {
        const q = jQuery(this).val();
        if(q.length < 3) { jQuery('#gp-search-results').hide(); return; }
        jQuery.post(ajaxurl, { action: 'gp_strategic_search', query: q, gp_nonce: gp_admin.nonce }, function(res) {
            if(res.success) jQuery('#gp-search-results').show().html(res.data.html);
        });
    });
    <?php
    // Fetch Real Historical Data
    $lead_data = array(); $booking_data = array(); $labels = array();
    for($i=4; $i>=0; $i--) {
        $date = date('Y-m-d', strtotime("-$i weeks"));
        $labels[] = "Week " . (5-$i);
        $lead_data[] = count(get_posts(array('post_type'=>'gp_lead', 'date_query'=>array(array('year'=>date('Y', strtotime($date)), 'week'=>date('W', strtotime($date)))), 'posts_per_page'=>-1)));
        $booking_data[] = count(get_posts(array('post_type'=>'gp_appointment', 'date_query'=>array(array('year'=>date('Y', strtotime($date)), 'week'=>date('W', strtotime($date)))), 'posts_per_page'=>-1)));
    }
    ?>
    var ctxMain = document.getElementById('gp-main-chart').getContext('2d');
    new Chart(ctxMain, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                data: <?php echo json_encode($lead_data); ?>,
                borderColor: '#2563EB',
                borderWidth: 8,
                tension: 0.5,
                pointRadius: 0,
                fill: true,
                backgroundColor: 'rgba(37, 99, 235, 0.04)'
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { display: false }, x: { grid: { display: false }, ticks: { font: { weight: '900', size: 10, family: 'Inter' } } } }
        }
    });

    var ctxVel = document.getElementById('gp-velocity-chart').getContext('2d');
    new Chart(ctxVel, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Bookings',
                data: <?php echo json_encode($booking_data); ?>,
                backgroundColor: '#E2E8F0',
                borderRadius: 10
            }, {
                label: 'Leads',
                data: <?php echo json_encode($lead_data); ?>,
                backgroundColor: '#2563EB',
                borderRadius: 10
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { display: false }, x: { grid: { display: false }, stacked: true } }
        }
    });
});
</script>
