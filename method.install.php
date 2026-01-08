<?php
#---------------------------------------------------------------------------------------------------
# Module: LogWatch
# Authors: Magal Hezi, with CMS Made Simple Foundation.
# Copyright: (C) 2025 Pixel Solutions, info@pixelsolutions.biz
# License: GNU General Public License version 2
#          see /LogWatch/README.md or <http://www.gnu.org/licenses/gpl-2.0.html>
#---------------------------------------------------------------------------------------------------
# CMS Made Simple(TM) is (c) CMS Made Simple Foundation 2004-2020 (info@cmsmadesimple.org)
# Project's homepage is: http://www.cmsmadesimple.org
#---------------------------------------------------------------------------------------------------
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# However, as a special exception to the GPL, this software is distributed
# as an addon module to CMS Made Simple. You may not use this software
# in any Non GPL version of CMS Made simple, or in any version of CMS
# Made simple that does not indicate clearly and obviously in its admin
# section that the site was built with CMS Made simple.
#---------------------------------------------------------------------------------------------------

if( !defined('CMS_VERSION') ) exit;

#Set Permission
$this->CreatePermission(LogWatch::MANAGE_PERM,'Manage LogWatch');
$this->CreatePermission(LogWatch::EXPORT_LOGS, 'Export Logs');

$default_logsettings = 'E_ALL';
$this->RegisterEvents();
$this->SetPreference('logsettings', $default_logsettings);

// Set default log source to first available
$available_logs = LogWatch::detectAvailableLogFiles();
if (!empty($available_logs)) {
    $first_log = array_keys($available_logs)[0];
    $this->SetPreference('log_source', $first_log);
}

// Track installation
include_once(dirname(__FILE__) . '/lib/class.ModuleTracker.php');
ModuleTracker::track('LogWatch', 'install');

?>