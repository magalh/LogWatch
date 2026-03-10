{if $pro_available}
    <div class="pagecontainer">
        <div class="information" style="margin: 20px 0;">
            <h3>LogWatch Pro is Installed</h3>
            <p>To configure LogWatch Pro features including Slack/Discord integrations, please visit:</p>
            <p><a href="{cms_action_url module='LogWatchPro' action='defaultadmin'}" class="ui-button ui-widget ui-state-default ui-corner-all">Go to LogWatch Pro Settings</a></p>
        </div>
    </div>
{else}
{* LogWatchPro not installed - show upsell *}
{literal}<style>
a.cta{display:inline-block;position:relative;margin:10px 0;line-height:26px;text-decoration:none;padding:0 8px 0 24px;clear:both;text-align:left}
a.cta .ui-myicon{position:absolute;left:1px;top:3px;color:#FFF!important}
a.cta .ui-myicon.my-icon-star{background:none;width:20px;height:20px;font-size:20px;line-height:20px;text-align:center}
a.cta .ui-myicon.my-icon-star::before{content:"★";display:block}
a.cta:hover{color:#fff}
a.cta.xlarge{padding:10px 20px 10px 40px}
a.cta.xlarge .ui-button-text{font-size:1.3em;letter-spacing:.03rem;text-transform:uppercase}
a.cta.xlarge .ui-myicon.my-icon-star{top:14px;left:14px;font-size:28px}
.ui-state-highlight,.ui-widget-content .ui-state-highlight,.ui-widget-header .ui-state-highlight{border:1px solid #8BC34A;background:#A4D061;color:#FFF!important}
.ui-state-highlight:hover{background:#B5DC72}
.premium ul{list-style:disc inside!important;text-align:left;margin:0!important;font-size:12px}
.premium li{display:inline}
.premium ul li::after{content:" | ";color:#CCC}
.premium ul li:last-child::after{content:""}
.premium a{font-weight:normal}
.premium .cta__bottom{background:#f9f9f9;padding:5px 30px}
.cta__top{display:flex;align-items:center;justify-content:space-between;padding:18px 30px}
.cta__action{position:relative;text-align:center}
.cta__top h2{font-size:28px;font-weight:200;line-height:1;margin:0 0 5px;letter-spacing:.05rem;color:#7CB342}
.lifted{border:1px solid rgba(0,0,0,.1);box-shadow:0 1px 1px 0 rgba(0,0,0,.1)}
</style>{/literal}

<div class="pagecontainer">
<section class="premium" style="margin-top: 20px; border-left: 4px solid #1e88e5;">
    <div class="lifted">
        <div class="cta__top">
            <div class="cta__summary">
                <h2 id="premium-upgrade-header">
                    LogWatch <strong>Pro</strong>
                </h2>
                <ul class="description_list">
                    <li><a href="https://pixelsolutions.biz/plugins/logwatch" target="_blank">View full Pro feature list</a></li>
                    <li><a href="https://pixelsolutions.biz/support" target="_blank">Professional support options</a></li>
                    <li><a href="https://pixelsolutions.biz/documentation/logwatch" target="_blank">Technical documentation</a></li>
                </ul>
            </div>
            <div class="cta__action">
                <a target="_blank" class="cta xlarge ui-state-highlight" data-icon="my-icon-star" href="https://pixelsolutions.biz/plugins/logwatch">
                    <span class="ui-myicon my-icon-star"></span>
                    <span class="ui-button-text">Get it here</span>
                </a>
            </div>
        </div>
        <div class="cta__bottom">
            <p class="premium-upgrade-prompt">
                You are currently using the Free version of LogWatch. 
                Upgrade to Pro for real-time notifications, error grouping, performance metrics, and priority support.
            </p>
        </div>
    </div>
</section>

<h3>Upgrade to LogWatch Pro</h3>
<p>Unlock enterprise-grade error monitoring features with LogWatch Pro:</p>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0;">
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h4>💬 Slack/Discord Integration</h4>
        <p>Send error notifications directly to your team's Slack or Discord channels for instant collaboration.</p>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h4>📧 Email Notifications</h4>
        <p>Receive instant email alerts when critical errors occur with detailed error information and stack traces.</p>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h4>🔗 Smart Error Grouping</h4>
        <p>Automatically group similar errors together to identify patterns and reduce noise in your logs.</p>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h4>📊 Analytics Dashboard</h4>
        <p>Visualize error trends, frequency patterns, and identify the most problematic areas of your application.</p>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h4>⚡ Performance Metrics</h4>
        <p>Track response times, memory usage, and execution performance alongside error logs.</p>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h4>📧 Scheduled Reports</h4>
        <p>Receive automated daily or weekly error summaries via email with actionable insights.</p>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h4>🔔 Notification History</h4>
        <p>Track all sent notifications with delivery status and timestamps for audit purposes.</p>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h4>⏰ Rate Limiting</h4>
        <p>Prevent notification spam with intelligent rate limiting and quiet hours configuration.</p>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h4>🎯 Error Level Filtering</h4>
        <p>Configure which error types trigger notifications (Fatal, Warning, Notice, Deprecated).</p>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h4>🚀 Real-Time Error Capture</h4>
        <p>Capture errors in real-time even without server log file access using PHP error handlers.</p>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h4>🔐 License Management</h4>
        <p>Easy license activation and management with automatic validation and renewal reminders.</p>
    </div>
    
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <h4>🎯 Priority Support</h4>
        <p>Get direct access to our development team for technical assistance and feature requests.</p>
    </div>
</div>

<div style="background: #f0f8ff; border: 2px solid #4a90e2; padding: 20px; border-radius: 5px; margin: 20px 0;">
    <h4 style="margin-top: 0;">How to Upgrade</h4>
    <ol>
        <li>Purchase LogWatch Pro license at <a href="https://pixelsolutions.biz/plugins/logwatch" target="_blank">pixelsolutions.biz</a></li>
        <li>Download and install the LogWatchPro module</li>
        <li>Enter your license key in the License tab</li>
        <li>All premium features will be unlocked automatically in this interface</li>
    </ol>
</div>

<p style="text-align: center; margin-top: 30px;">
    <a href="https://pixelsolutions.biz/plugins/logwatch" target="_blank" class="cta xlarge ui-state-highlight" data-icon="my-icon-star">
        <span class="ui-myicon my-icon-star"></span>
        <span class="ui-button-text">Get LogWatch Pro</span>
    </a>
</p>
</div>
{/if}
