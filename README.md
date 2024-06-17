<h2>Introduction</h2>
<p>The LogWatch module is designed to assist developers in troubleshooting PHP errors through a simple and user-friendly admin interface. This module allows you to specify the location of your PHP error log file, read the contents of the log, and display the errors in a more human-readable form within the CMS Made Simple admin panel. By providing an organized and accessible view of error logs, LogWatch helps developers quickly identify and resolve issues, improving the efficiency of the development process.</p>

<h3>Features</h3>
<ul>
    <li>Specify the location of the PHP error log file.</li>
    <li>Read and parse error log entries.</li>
    <li>Display errors in a structured and readable format.</li>
    <li>Paginate log entries for easier navigation.</li>
    <li>Highlight key information such as date, error type, message, file, and line number.</li>
</ul>

<h3>Usage</h3>
<p>To use the LogWatch module, simply configure the location of your PHP error log file in the module preferences. The module will then read and display the log entries in the admin interface, providing you with a clear and concise overview of any PHP errors that have occurred. This makes it easier to track down and fix issues, ensuring your CMS Made Simple site runs smoothly.</p>

<h2>Troubleshooting</h2>
<h3>Q1: How do I check if my log file path is correct?</h3>
<p>Ensure that the log file path specified in your CMS Made Simple module preferences is correct. You can check this manually by navigating to the specified path in your server. For example, if your log file is supposed to be at <code>/var/log/apache2/cmsms.com.error.log</code>, you can run:
<pre><code>ls -l /var/log/apache2/cmsms.com.error.log</code></pre><br>
If the file exists, it should be listed with its permissions.</p>

<h3>Q2: How do I check the permissions of my log file?</h3>
<p>Use the <code>ls -l</code> command to check the permissions of your log file:
<pre><code>ls -l /var/log/apache2/cmsms.com.error.log</code></pre><br>
The output will show the permissions, owner, and group of the file. For example:<br>
<pre><code>-rw-r--r-- 1 root root 12345 Jun 17 11:38 cmsms.com.error.log</code></pre><br>
This means the file is readable by everyone (<code>rw-r--r--</code>).</p>

<h3>Q3: How do I change the permissions of my log file to make it readable?</h3>
<p>Use the <code>chmod</code> command to change the permissions of the log file. To make the file readable by all users, run:
<pre><code>sudo chmod 644 /var/log/apache2/cmsms.com.error.log</code></pre><br></p>

<h3>Q4: How do I check the permissions of the directory containing my log file?</h3>
<p>Use the <code>ls -ld</code> command to check the permissions of the directory:
<pre><code>ls -ld /var/log/apache2</code></pre><br>
The output should show the directory permissions. For example:<br>
<pre><code>drwxr-xr-x 2 root root 4096 Jun 17 11:38 /var/log/apache2</code></pre><br>
This means the directory is accessible by everyone (<code>drwxr-xr-x</code>).</p>

<h3>Q5: How do I change the permissions of the directory to make it accessible?</h3>
<p>Use the <code>chmod</code> command to change the directory permissions. To make the directory accessible by all users, run:
<pre><code>sudo chmod 755 /var/log/apache2</code></pre><br></p>

<h3>Q6: How do I verify which user is running the PHP script?</h3>
<p>Add a debugging output in your PHP script to print the current user:
<pre><code>echo 'Script running as user: ' . get_current_user() . '&lt;br&gt;';</code></pre><br></p>

<h3>Q7: How do I verify if the PHP script can access the log file?</h3>
<p>Add debugging output in your PHP script to check if the file exists and is readable:
<pre><code>
$logfilepath = '/var/log/apache2/cmsms.com.error.log';

echo 'Log file path: ' . $logfilepath . '&lt;br&gt;';
echo 'File exists check: ' . (file_exists($logfilepath) ? 'Yes' : 'No') . '&lt;br&gt;';
echo 'File is readable check: ' . (is_readable($logfilepath) ? 'Yes' : 'No') . '&lt;br&gt;';
</code></pre><br></p>

<h3>Q8: What should I do if the file exists but is reported as not readable?</h3>
<p>Ensure that the PHP script's user has the necessary permissions to read the file. You may need to adjust the file and directory permissions as described in Q3 and Q5. Additionally, ensure there are no SELinux policies or AppArmor profiles restricting access.</p>

<h3>Q9: How do I debug directory contents visibility?</h3>
<p>Add debugging output in your PHP script to list the directory contents:
<pre><code>
echo 'Current directory contents:&lt;br&gt;';
print_r(scandir('/var/log/apache2'));
echo '&lt;br&gt;';
</code></pre><br>
This will help you confirm that PHP can see the file and directory correctly.</p>

<h3>Q10: What should I do if I still cannot read the log file after checking permissions?</h3>
<p>If permissions are correctly set and the file still cannot be read, consider the following:
<ul>
<li>Verify that the file path is absolutely correct.</li>
<li>Check for SELinux policies or AppArmor profiles that might restrict access.</li>
<li>Ensure that the web server user (e.g., <code>www-data</code> for Apache on Ubuntu) has sufficient permissions.</li>
<li>Check the server logs for any error messages related to file access.</li>
</ul>
By following these steps and answers, you should be able to troubleshoot and resolve most log file permission issues in CMS Made Simple.</p>

<h3>Feedback/Support</h3>
<p>This module does not include commercial support. However, there are a number of resources available to help you with it:</p>
<ul>
<li>For the latest version of this module or to file a Feature Request or Bug Report, please visit the Module Forge
<a href="http://dev.cmsmadesimple.org/projects/LogWatch" target="_blank">LogWatch Page</a>.</li>
<li>Additional discussion of this module may also be found in the <a href="https://forum.cmsmadesimple.org/viewtopic.php?f=7&t=83788">LogWatch forum topic</a>. You are warmly invited to open a new topic if you didn't find an answer to your question.</li>
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
