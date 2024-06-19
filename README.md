<h2>Introduction</h2>
<p>The LogWatch module is designed to assist developers in troubleshooting PHP errors through a simple and user-friendly admin interface. This module allows you to specify the location of your PHP error log file, read the contents of the log, and display the errors in a more human-readable form within the CMS Made Simple admin panel. By providing an organized and accessible view of error logs, LogWatch helps developers quickly identify and resolve issues, improving the efficiency of the development process.</p>

<h3>Features</h3>
<ul>
    <li>Read and parse error log entries.</li>
    <li>Display errors in a structured and readable format.</li>
    <li>Paginate log entries for easier navigation.</li>
    <li>Highlight key information such as date, error type, message, file, and line number.</li>
</ul>

<h3>Usage</h3>
<p>To use the LogWatch module, simply add to your template the {LogWatch} tag. The module will then read and display the log entries in the admin interface, providing you with a clear and concise overview of any PHP errors that have occurred. This makes it easier to track down and fix issues, ensuring your CMS Made Simple site runs smoothly.</p>
<pre><code>
&lt;!doctype html&gt;
{strip}
{process_pagedata}
<strong>{LogWatch}</strong>
....
</code></pre>

<h3>Feedback/Support</h3>
<p>This module does not include commercial support. However, there are a number of resources available to help you with it:</p>
<ul>
  <li>For the latest version of this module or to file a Feature Request or Bug Report, please visit the <a href="https://github.com/magalh/LogWatch" target="_blank">LogWatch GitHub Page</a>.</li>
    <li>If you didn't find an answer to your question, you are warmly invited to open a new issue on the <a href="https://github.com/magalh/LogWatch/issues" target="_blank">LogWatch GitHub Issues Page</a>.</li>
</ul>

<li>Lastly, if you enjoy this module, use it on a commercial website or would like to encourage future development, you might consider just a small donation. Any kind of feedback will be much appreciated.<br>
<a href="https://www.paypal.com/donate/?hosted_button_id=FWHABZUN3NC4N" target="_blank"><img src="https://raw.githubusercontent.com/aha999/DonateButtons/master/paypal-donate-icon-7.png" width="120" ></a><br>
	</li>
</ul>

<h3>Copyright and License</h3>
<p>Copyright &copy; 2024, Magal Hezi <a href="mailto:h_magal@hotmail.com">&lt;h_magal@hotmail.com&gt;</a>. All Rights Are Reserved.</p>
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
