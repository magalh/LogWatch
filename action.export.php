<?php
#--------------------------------------------------
# See doc/LICENSE for full license information.
#--------------------------------------------------
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