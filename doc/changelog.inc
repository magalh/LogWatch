<ul>
 <li>2.0.2 - January 2025
    <p>- Implemented separate PHP and Apache log parsers for better accuracy</p>
    <p>- Added PhpLogParser class for multiline PHP error logs with stack traces</p>
    <p>- Added ApacheLogParser class using Kassner LogParser library</p>
    <p>- Improved automatic log format detection in FileQuery</p>
    <p>- Enhanced parsing of PHP error logs with timezone support</p>
    <p>- Fixed multiline stack trace handling for PHP errors</p>
  </li>
 <li>2.0.1 - January 2025
    <p>- Added manual log file path configuration for shared hosting compatibility</p>
    <p>- Created separate Filters tab for better UI organization</p>
    <p>- Improved Italian language translations</p>
    <p>- Enhanced admin interface with proper error alerts and validation</p>
  </li>
 <li>2.0.0 - January 2025
    <p>- Complete rewrite with modern log parsing using kassner/log-parser library</p>
    <p>- Added support for multiple log sources (Apache, PHP ini, virtual host, document root)</p>
    <p>- Improved error type detection and filtering (Fatal Error, Warning, Notice, Deprecated)</p>
    <p>- Enhanced user interface with module icon and clean design</p>
    <p>- Added modal popup for detailed error view with stack traces</p>
    <p>- Updated CSV export functionality to work with new parsing system</p>
    <p>- Removed individual log deletion (read-only approach for server logs)</p>
    <p>- Added automatic log file detection and configuration</p>
    <p>- Implemented proper HTML escaping for security</p>
    <p>- Added composer integration for robust log parsing</p>
  </li>
 <li>1.4.0 - December 2024
    <p>- Added an option to clear all errors</p>
    <p>- Added an option to export all logs</p>
    <p>- Added new sub permissions</p>
  </li>
  <li>1.3.0 - July 2024
    <p>- Global Error Handler Initialization</p>
    <p>- Static Variable for Single Initialization</p>
    <p>- Remove Redundant Initialization</p>
    <p>- Autoloading and Smarty Integration</p>
    <p>- Improved Error Handling Logic</p>
  </li>
  <li>1.2.0 - July 2024
    <p>- Made the module a registeredEvent</p>
    <p>- Created a testing platform on {LogWatch} default action</p>
    <p>- Made E_ALL the default selection</p>
  </li>
  <li>1.0.0 - June 2024
    <p>Initial Release</p>
  </li>
</ul>
