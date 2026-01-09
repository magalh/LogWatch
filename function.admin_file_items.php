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

$error = 0;
$message = '';

$tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_file_items.tpl'),null,null,$smarty);

$pagelimit = 50;
$pagenumber = 1;

if (isset($params['pagenumber']) && $params['pagenumber'] !== '') {
    $pagenumber = (int)$params['pagenumber'];
}

$offset = ($pagenumber - 1) * $pagelimit;

// Get selected log source
$selected_log_source = $this->GetPreference('log_source', '');
$available_logs = LogWatch::detectAvailableLogFiles();

// Handle manual log path
if ($selected_log_source === 'manual') {
    $manual_path = $this->GetPreference('manual_log_path', '');
    if (!empty($manual_path)) {
        $available_logs['manual'] = [
            'name' => 'Manual Log Path',
            'path' => $manual_path,
            'type' => 'manual',
            'exists' => file_exists($manual_path) && is_readable($manual_path)
        ];
    }
}

// Auto-select first available if none selected
if (empty($selected_log_source) || !isset($available_logs[$selected_log_source])) {
    $first_available = array_key_first($available_logs);
    if ($first_available) {
        $selected_log_source = $first_available;
        $this->SetPreference('log_source', $selected_log_source);
    }
}

// Get filter settings
$logsettings = $this->GetPreference('logsettings', 'E_ALL');
$selected_filters = explode(',', $logsettings);
$show_all = in_array('E_ALL', $selected_filters);

$logs = [];
if ($selected_log_source && isset($available_logs[$selected_log_source])) {
    $log_file_path = $available_logs[$selected_log_source]['path'];
    $logQuery = new FileQuery($log_file_path);
    $all_logs = $logQuery->parseLogFile();
    
    // Apply error type filter
    if (!$show_all && !empty($selected_filters)) {
        $all_logs = array_filter($all_logs, function($log) use ($selected_filters) {
            return in_array($log->type, $selected_filters) || 
                   in_array('E_' . strtoupper($log->type), $selected_filters);
        });
    }
    
    $total_items = is_array($all_logs) ? count($all_logs) : 0;
    $logs = is_array($all_logs) ? array_slice($all_logs, $offset, $pagelimit) : [];
} else {
    $total_items = 0;
}

$total_pages = ceil($total_items / $pagelimit);

$tpl->assign('message',$message);
$tpl->assign('error',$error);
$tpl->assign('logs',$logs);
$tpl->assign('pagenumber', $pagenumber);
$tpl->assign('total_items', $total_items);
$tpl->assign('total_pages', $total_pages);
$tpl->assign('selected_log_info', $available_logs[$selected_log_source] ?? null);

$clear_logs = false;
if( $this->CheckPermission(LogWatch::CLEAR_LOGS) ) {
    $clear_logs = true;
    $tpl->assign('clear_logs', $clear_logs);
}
$export_logs = false;
if( $this->CheckPermission(LogWatch::EXPORT_LOGS) ) {
    $export_logs = true;
    $tpl->assign('export_logs', $export_logs);
}

$tpl->display();

?>