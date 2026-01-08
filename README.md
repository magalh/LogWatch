<h2>Introduction</h2>
<p>The LogWatch module is designed to assist developers in troubleshooting PHP errors through a simple and user-friendly admin interface. This module allows you to capture and display PHP errors directly within the CMS Made Simple admin panel, presenting them in a more human-readable form. By providing an organized and accessible view of errors, LogWatch helps developers quickly identify and resolve issues, improving the efficiency of the development process.</p>

<h3>Features</h3>
<ul>
    <li>Read and parse error log entries.</li>
    <li>Display errors in a structured and readable format.</li>
    <li>Paginate log entries for easier navigation.</li>
    <li>Highlight key information such as date, error type, message, file, and line number.</li>
</ul>

<h3>Usage</h3>
<p>LogWatch monitors server error logs and displays them in an organized, easy-to-read format within the CMS Made Simple admin panel.</p>

<h4>Viewing Logs</h4>
<ol>
    <li>Navigate to <strong>Site Admin &gt; LogWatch</strong></li>
    <li>Select the <strong>Logs</strong> tab to view current errors</li>
    <li>Click the <strong>View</strong> button next to any error to see full details including stack traces</li>
    <li>Use the <strong>Export CSV</strong> button to download logs for external analysis</li>
</ol>

<h3>Screenshots</h3>
<p><img src="https://cmsms-downloads.s3.eu-south-1.amazonaws.com/LogWatch/thumbnail.jpg" alt="LogWatch module" width="900"></p>

<h4>Configuration</h4>
<ol>
    <li>Go to the <strong>Settings</strong> tab</li>
    <li>Select your preferred <strong>Log Source</strong> from available options (Apache logs, PHP ini logs, etc.)</li>
    <li>Choose which <strong>Error Types</strong> to display (Fatal Error, Warning, Notice, Deprecated)</li>
    <li>Click <strong>Save</strong> to apply your settings</li>
</ol>

<p>The module automatically detects available log files on your server and supports multiple log formats including Apache error logs, PHP ini error logs, and document root error logs.</p>

<h3>Testing</h3>
<p>For testing and debugging purposes, you might want to manually trigger some errors to see how the LogWatch module captures and displays them. To do this, you can use the {LogWatch} tag on any of your front-end pages. This tag will display a series of action buttons that allow you to trigger different types of PHP errors manually. These buttons are useful for verifying that the module is correctly logging errors and for testing various error handling scenarios.</p>
<p>By providing a comprehensive and customizable error logging solution, the LogWatch module helps developers efficiently identify and resolve issues, improving the overall development and maintenance process.</p>

<h3>Feedback/Support</h3>
<p>This module does not include commercial support. However, there are a number of resources available to help you with it:</p>
<ul>
  <li>For the latest version of this module or to file a Feature Request or Bug Report, please visit the <a href="https://github.com/magalh/LogWatch" target="_blank">LogWatch GitHub Page</a>.</li>
    <li>If you didn't find an answer to your question, you are warmly invited to open a new issue on the <a href="https://github.com/magalh/LogWatch/issues" target="_blank">LogWatch GitHub Issues Page</a>.</li>

<li>Lastly, if you enjoy this module, use it on a commercial website or would like to encourage future development, you might consider just a small donation. Any kind of feedback will be much appreciated.<br>
<a href="https://www.paypal.com/donate/?hosted_button_id=FWHABZUN3NC4N" target="_blank"><img src="https://raw.githubusercontent.com/aha999/DonateButtons/master/paypal-donate-icon-7.png" width="120" ></a><br>
	</li>
</ul>

<h3>Credits</h3>
<p>This module uses the <a href="https://github.com/kassner/log-parser" target="_blank">kassner/log-parser</a> library for robust Apache log parsing. Special thanks to the contributors of this excellent library.</p>

<h3>Copyright and License</h3>
<p>Copyright &copy; 2024, Magal Hezi <a href="mailto:magal@pixelsolutions.biz">&lt;magal@pixelsolutions.biz&gt;</a>. All Rights Are Reserved.</p>
<p>This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.</p>
<p>However, as a special exception to the GPL, this software is distributed
as an addon module to CMS Made Simple.  You may not use this software
in any Non GPL version of CMS Made simple, or in any version of CMS
Made simple that does not indicate clearly and obviously in its admin
section that the site was built with CMS Made simple.</p>
<p>This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
Or read it <a href="http://www.gnu.org/licenses/licenses.html#GPL">online</a>
</p>