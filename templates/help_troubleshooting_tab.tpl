<h3>{$logwatch->Lang('help_troubleshooting')}</h3>

<h4>No Log Files Detected</h4>
<p><strong>Problem</strong>: The log source dropdown is empty or doesn't show your log file.</p>
<p><strong>Solutions</strong>:</p>
<ul>
  <li>Check that error logging is enabled in php.ini: <code>log_errors = On</code></li>
  <li>Verify error_log path in php.ini: <code>error_log = /path/to/error.log</code></li>
  <li>Ensure the web server has read permissions on the log file</li>
  <li>Check for open_basedir restrictions in PHP configuration</li>
  <li>Try creating an error_log file in your CMS root directory</li>
</ul>

<h4>Errors Not Appearing</h4>
<p><strong>Problem</strong>: PHP errors are occurring but not showing in LogWatch.</p>
<p><strong>Solutions</strong>:</p>
<ul>
  <li>Verify the correct log source is selected in Settings tab</li>
  <li>Check that the error type filter includes the error level you're looking for</li>
  <li>Ensure the error isn't hidden (check Hidden Errors tab)</li>
  <li>Confirm PHP error_reporting level includes the error type</li>
  <li>Use the <code>{literal}{LogWatch}{/literal}</code> tag to manually trigger test errors</li>
</ul>

<h4>Permission Denied Errors</h4>
<p><strong>Problem</strong>: "Permission denied" or "Failed to open stream" errors.</p>
<p><strong>Solutions</strong>:</p>
<ul>
  <li>Ensure the web server user (www-data, apache, nginx) can read the log file</li>
  <li>Check file permissions: <code>chmod 644 /path/to/error.log</code></li>
  <li>Check directory permissions: <code>chmod 755 /path/to/logs/</code></li>
  <li>Verify SELinux isn't blocking access (if applicable)</li>
</ul>

<h4>Large Log Files / Performance Issues</h4>
<p><strong>Problem</strong>: LogWatch is slow or times out with large log files.</p>
<p><strong>Solutions</strong>:</p>
<ul>
  <li>Reduce "Items Per Page" in Settings tab (try 25 or 10)</li>
  <li>Rotate/archive old log files regularly</li>
  <li>Filter to show only Fatal/Warning errors (uncheck Notice/Deprecated)</li>
  <li>Increase PHP max_execution_time if needed</li>
  <li>Consider using logrotate to manage log file sizes</li>
</ul>

<h4>CSV Export Not Working</h4>
<p><strong>Problem</strong>: Export CSV button doesn't download file.</p>
<p><strong>Solutions</strong>:</p>
<ul>
  <li>Check that you have "Export Logs" permission</li>
  <li>Verify no output is sent before headers (check for PHP warnings)</li>
  <li>Try with a smaller date range or fewer error types</li>
  <li>Check browser console for JavaScript errors</li>
</ul>

<h4>LogWatchPro Issues</h4>

<p><strong>License Not Validating</strong>:</p>
<ul>
  <li>Verify license key is entered correctly (no extra spaces)</li>
  <li>Check that your server can reach api.pixelsolutions.biz</li>
  <li>Ensure license hasn't expired</li>
  <li>Verify site URL matches licensed domain</li>
  <li>Contact support if issues persist</li>
</ul>

<p><strong>Notifications Not Sending</strong>:</p>
<ul>
  <li>Use the "Send Test" button to verify webhook/email configuration</li>
  <li>Check Notification History tab for error messages</li>
  <li>Verify webhook URLs are correct and accessible</li>
  <li>Check rate limiting settings (may be blocking notifications)</li>
  <li>Ensure Pro features are enabled (green toggle in Settings)</li>
  <li>Verify error type matches notification filters</li>
</ul>

<p><strong>Analytics Not Showing Data</strong>:</p>
<ul>
  <li>Analytics only track errors going forward (no historical import)</li>
  <li>Ensure error grouping is enabled in Settings</li>
  <li>Wait for errors to occur after enabling Pro features</li>
  <li>Check that error types match notification filters</li>
  <li>Try different time period (7/30/90 days)</li>
</ul>

<h4>Getting Help</h4>
<p><strong>Free Version</strong>:</p>
<ul>
  <li>GitHub Issues: <a href="https://github.com/magalh/LogWatch/issues" target="_blank">github.com/magalh/LogWatch/issues</a></li>
  <li>Community Forums: <a href="https://forum.cmsmadesimple.org" target="_blank">forum.cmsmadesimple.org</a></li>
</ul>

<p><strong>Pro Version</strong>:</p>
<ul>
  <li>Priority Email Support: <a href="mailto:info@pixelsolutions.biz">info@pixelsolutions.biz</a></li>
  <li>Include: CMSMS version, PHP version, error messages, screenshots</li>
</ul>
