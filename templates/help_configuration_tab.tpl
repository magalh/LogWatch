<h3>{$logwatch->Lang('help_configuration')}</h3>

<h4>Settings Tab</h4>

<p><strong>Log Source</strong></p>
<p>Select which log file to monitor from the dropdown. LogWatch automatically detects available logs on your server. If your desired log isn't listed, ensure:</p>
<ul>
  <li>The file exists and is readable by the web server</li>
  <li>PHP has permission to access the file</li>
  <li>The path is not blocked by open_basedir restrictions</li>
</ul>

<p><strong>Error Types</strong></p>
<p>Choose which error types to display:</p>
<ul>
  <li><strong>Fatal Error</strong>: Critical errors that stop script execution</li>
  <li><strong>Warning</strong>: Non-fatal errors that allow script to continue</li>
  <li><strong>Notice</strong>: Minor issues like undefined variables</li>
  <li><strong>Deprecated</strong>: Features that will be removed in future PHP versions</li>
</ul>

<p><strong>Items Per Page</strong></p>
<p>Control how many log entries display per page (default: 50). Lower values improve performance with large log files.</p>

<h4>Logs Tab</h4>

<p><strong>Filter Checkboxes</strong></p>
<p>Use the checkboxes at the top of the Logs tab to quickly filter which error types are displayed. Click <strong>Apply</strong> to update the view.</p>

<p><strong>View Button</strong></p>
<p>Click the <strong>View</strong> button next to any error to see:</p>
<ul>
  <li>Full error message</li>
  <li>Complete stack trace</li>
  <li>File path and line number</li>
  <li>Timestamp</li>
</ul>

<p><strong>Hide Button</strong></p>
<p>Click <strong>Hide</strong> to suppress an error from future views. Hidden errors can be managed in the <strong>Hidden Errors</strong> tab.</p>

<p><strong>Export CSV</strong></p>
<p>Download all visible errors as a CSV file for analysis in spreadsheet applications.</p>

<h4>Hidden Errors Tab</h4>
<p>View and manage all errors you've hidden. Click <strong>Unhide</strong> to restore an error to the main logs view.</p>

<h4>Permissions</h4>
<ul>
  <li><strong>Manage LogWatch</strong>: Full access to all features</li>
  <li><strong>Export Logs</strong>: Permission to download CSV exports</li>
</ul>
