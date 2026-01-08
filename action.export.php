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
if( !$this->CheckPermission(LogWatch::EXPORT_LOGS) ) return;

try {
    // Get selected log source
    $selected_log_source = $this->GetPreference('log_source', '');
    $available_logs = LogWatch::detectAvailableLogFiles();
    
    if (empty($selected_log_source) || !isset($available_logs[$selected_log_source])) {
        throw new Exception('No log source selected');
    }
    
    $log_file_path = $available_logs[$selected_log_source]['path'];
    if (!file_exists($log_file_path) || !is_readable($log_file_path)) {
        throw new Exception('Log file not readable');
    }

    // Set headers for CSV download
    $timestamp = date('Y-m-d_H-i-s');
    $filename = "logwatch_export_{$timestamp}.csv";
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Write CSV header
    fputcsv($output, array(
        'Date',
        'Type',
        'Message',
        'File',
        'Line',
        'Full Details'
    ));

    // Parse log file using ServerLogParser
    $logQuery = new FileQuery($log_file_path);
    $logs = $logQuery->parseLogFile();
    
    foreach ($logs as $log) {
        fputcsv($output, array(
            date('Y-m-d H:i:s', $log->created),
            $log->type,
            $log->description,
            $log->file,
            $log->line,
            strip_tags(str_replace('<br>', "\n", $log->stacktrace))
        ));
    }

    fclose($output);
    exit();

} catch (Exception $e) {
    $this->SetError('Error exporting logs: ' . $e->getMessage());
    $this->RedirectToAdminTab();
}

?>