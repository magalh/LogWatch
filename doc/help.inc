<h3>LogWatch Module</h3>
<p>LogWatch monitors server error logs and displays them in an organized, easy-to-read format within the CMS Made Simple admin panel.</p>

<h4>Features</h4>
<ul>
    <li>Multi-format log parsing (Apache, Nginx, PHP, Syslog)</li>
    <li>Grouped error view with occurrence counts, first/last seen timestamps</li>
    <li>Error hiding/suppression system with persistent tracking</li>
    <li>Custom error handler for environments without server log access</li>
    <li>CSV export for external analysis</li>
    <li>Automatic log file detection</li>
</ul>

<h4>Viewing Logs</h4>
<ol>
    <li>Navigate to <strong>Extensions &gt; LogWatch</strong></li>
    <li>Select the <strong>Logs</strong> tab to view current errors</li>
    <li>Errors are grouped by default &mdash; click to expand individual occurrences</li>
    <li>Click the <strong>View</strong> button next to any error to see full details including stack traces</li>
    <li>Use the <strong>Hide</strong> button to suppress known/expected errors</li>
    <li>Use the <strong>Export CSV</strong> button to download logs for external analysis</li>
</ol>

<h4>Configuration</h4>
<ol>
    <li>Go to the <strong>Settings</strong> tab</li>
    <li>Select your preferred <strong>Log Source</strong> from available options (Apache, PHP ini, virtual host, custom handler)</li>
    <li>Choose which <strong>Error Types</strong> to display (Fatal Error, Warning, Notice, Deprecated)</li>
    <li>Optionally enable the <strong>Custom Error Handler</strong> to capture errors to a local log file</li>
    <li>Click <strong>Save</strong> to apply your settings</li>
</ol>

<h4>Hidden Errors</h4>
<p>The <strong>Hidden Errors</strong> tab shows all suppressed errors. You can unhide errors at any time to restore them to the main log view.</p>

<h4>Testing</h4>
<p>Use the <code>{LogWatch}</code> tag on any front-end page to display test buttons that trigger different PHP error types (Fatal, Warning, Notice, Deprecated, Parse).</p>
