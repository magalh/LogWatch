<h3>{$logwatch->Lang('help_features')}</h3>

<h4>Log Viewing</h4>
<ul>
  <li><strong>Multi-Format Support</strong>: Parses Apache, Nginx, PHP, and Syslog formats</li>
  <li><strong>Pagination</strong>: Navigate large log files efficiently</li>
  <li><strong>Error Filtering</strong>: Show/hide specific error types (Fatal, Warning, Notice, Deprecated)</li>
  <li><strong>Detail View</strong>: Click any error to see full stack traces and context</li>
  <li><strong>Real-Time</strong>: Reads directly from log files (no database storage)</li>
</ul>

<h4>Error Management</h4>
<ul>
  <li><strong>Grouped View</strong>: Identical errors are grouped together with occurrence counts</li>
  <li><strong>Hide Errors</strong>: Suppress known/acknowledged errors from view</li>
  <li><strong>Error Hash System</strong>: Tracks unique errors across log rotations, normalizes dynamic parts (client IP, PID) for accurate grouping</li>
  <li><strong>Hidden Errors Tab</strong>: Manage all suppressed errors in one place</li>
  <li><strong>CSV Export</strong>: Download logs for external analysis</li>
</ul>

<h4>Custom Error Handler</h4>
<p>When server error logs aren't accessible (common on shared hosting), LogWatch can capture PHP errors directly using its built-in error handler:</p>
<ul>
  <li>Captures Fatal errors, Warnings, Notices, Deprecated, and Parse errors</li>
  <li>Writes to a local log file in standard PHP error log format</li>
  <li>Log file stored in <code>tmp/cache/logwatch/php-errors.log</code>, protected with .htaccess</li>
  <li>Enable it by selecting <strong>Custom Error Handler</strong> as your log source in Settings</li>
  <li>Works in both admin and frontend</li>
</ul>

<h4>Auto-Detection</h4>
<p>LogWatch automatically detects available log files on your server:</p>
<ul>
  <li>Virtual host-specific logs (prioritized by domain)</li>
  <li>PHP ini error_log setting</li>
  <li>CMS root directory logs</li>
  <li>System logs (Apache/Nginx)</li>
  <li>Home directory log patterns</li>
</ul>

<h4>Testing Tools</h4>
<p>Use the <code>{literal}{LogWatch}{/literal}</code> tag on any frontend page to manually trigger test errors:</p>
<ul>
  <li>Fatal Error</li>
  <li>Warning</li>
  <li>Notice</li>
  <li>Deprecated</li>
  <li>Parse Error</li>
</ul>
