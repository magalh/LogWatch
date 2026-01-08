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
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

if( isset($params['submit']) ) {
    $logsettings = isset($params['logsettings']) ? $params['logsettings'] : [];
    $this->SetPreference('logsettings', implode(',', $logsettings));
    
    $selected_log_source = isset($params['log_source']) ? $params['log_source'] : 'logwatch';
    $this->SetPreference('log_source', $selected_log_source);
    
    $this->SetMessage("Saved");
    $this->RedirectToAdminTab();
}

$logsettings = $this->GetPreference('logsettings', 'E_ALL');
$selected_logsettings = explode(',', $logsettings);
$selected_log_source = $this->GetPreference('log_source', '');

// Auto-select first available log if none selected
if (empty($selected_log_source)) {
    $available_logs = LogWatch::detectAvailableLogFiles();
    $first_available = array_key_first($available_logs);
    if ($first_available) {
        $selected_log_source = $first_available;
        $this->SetPreference('log_source', $selected_log_source);
    }
}

$exceptions = [
    'E_ALL' => 'All Error Types',
    'Fatal error' => 'Fatal Error',
    'Warning' => 'Warning', 
    'Deprecated' => 'Deprecated',
    'Notice' => 'Notice',
    'Error' => 'Error'
];

$available_logs = LogWatch::detectAvailableLogFiles();

$tpl = $smarty->CreateTemplate( $this->GetTemplateResource('admin_settings_tab.tpl'), null, null, $smarty );
$tpl->assign('selected_logsettings', $selected_logsettings);
$tpl->assign('exceptions', $exceptions);
$tpl->assign('available_logs', $available_logs);
$tpl->assign('selected_log_source', $selected_log_source);
$tpl->display();

?>