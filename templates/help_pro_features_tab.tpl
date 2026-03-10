<h3>{$logwatch->Lang('help_pro_features')}</h3>

<div style="background: #e8f5e9; border-left: 4px solid #4caf50; padding: 15px; margin: 15px 0;">
  <strong>Works Without Log File Access!</strong><br>
  LogWatchPro's real-time error handler works even if you don't have access to server log files. It captures NEW errors as they occur and sends notifications, tracks analytics, and groups errors - no log file reading required.
</div>

<h4>Real-Time Notifications</h4>
<p>Get instant alerts when errors occur via:</p>
<ul>
  <li><strong>Slack</strong>: Send formatted error notifications to team channels</li>
  <li><strong>Discord</strong>: Webhook integration with color-coded embeds</li>
  <li><strong>Email</strong>: HTML email notifications with customizable templates</li>
</ul>

<p><strong>Configuration</strong>:</p>
<ul>
  <li>Enable/disable each notification channel independently</li>
  <li>Filter by error type (only send Fatal/Warning, or include all)</li>
  <li>Test connections with one-click test buttons</li>
</ul>

<h4>Rate Limiting</h4>
<p>Prevent notification spam with intelligent rate limiting:</p>
<ul>
  <li><strong>Per-Error Cooldown</strong>: Don't re-notify for same error within X minutes (default: 5)</li>
  <li><strong>Global Hourly Limit</strong>: Maximum notifications per hour (default: 20)</li>
  <li>Configurable thresholds in Settings tab</li>
</ul>

<h4>Notification History</h4>
<p>Track all notification delivery attempts:</p>
<ul>
  <li>View sent/failed status for each notification</li>
  <li>See which channel was used (Slack/Discord/Email)</li>
  <li>Paginated history with timestamps</li>
  <li>Automatic cleanup after 30 days (configurable)</li>
</ul>

<h4>Error Grouping & Analytics</h4>
<p>Intelligent error analysis and visualization:</p>
<ul>
  <li><strong>Smart Grouping</strong>: Automatically group similar errors together</li>
  <li><strong>Occurrence Tracking</strong>: Count how many times each error pattern appears</li>
  <li><strong>Visual Analytics</strong>: 4 interactive Chart.js dashboards:
    <ul>
      <li>Errors by Type (pie chart)</li>
      <li>Top Errors (doughnut chart)</li>
      <li>Errors Timeline (line chart)</li>
      <li>Notification Stats (bar chart)</li>
    </ul>
  </li>
  <li><strong>Time Period Selector</strong>: View data for 7, 30, or 90 days</li>
</ul>

<h4>Automatic Cleanup</h4>
<p>Daily cron job maintains database health:</p>
<ul>
  <li>Deletes notification history older than retention period (default: 30 days)</li>
  <li>Removes inactive error groups (not seen in 90+ days)</li>
  <li>Keeps database size manageable</li>
</ul>

<h4>Pro Status Toggle</h4>
<p>Enable/disable Pro features without uninstalling:</p>
<ul>
  <li>Toggle in Settings tab (green = On, orange = Off)</li>
  <li>Separate from license validation</li>
  <li>Useful for temporary troubleshooting</li>
</ul>

<h4>Support</h4>
<p>LogWatchPro includes priority support:</p>
<ul>
  <li>Direct email support: <a href="mailto:info@pixelsolutions.biz">info@pixelsolutions.biz</a></li>
  <li>Faster response times</li>
  <li>Feature requests prioritized</li>
</ul>
